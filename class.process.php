<?php 
// The process class handles all transactions to the database
// The functions that start with get, feth rows from the database,
// while those that start with set, perform an Update or an Insert depending on the values passed to it.

require_once('./class.ilog.php');

class Process{

    private $conn;
    private $status;
    private $log;
    private $pepper = '3xp0rtBe71z3';

    function __construct(){

        $this->status = true;

        try {
            $this->conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $this->log = new iLog();
          
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            $this->status = false;
            die();

        }
    }
    // Validates login
    public function validateLogin ($data = null){

        $email = $this->sanitize($data['email']) ?? '';
        $pass = $data['password'] ?? '';

        $result = $this->getUserSalt($email);

        if ($result != false ){
            
            $pass = md5($pass.$result['salt'].$this->pepper);
            
            //salt was returned
            $sql = 'SELECT id AS user_id, full_name, email, user_type FROM users u WHERE u.email = ? AND u.password = ?';
            $query = $this->conn->prepare($sql);
            $query->execute([$email, $pass]);

            if ($query->rowCount() > 0 ){
                return $query->fetch();
            }
            $this->log->info('User: '.$email.' Failed login attempt');
        }
        return false;

    }
    //creates the buyer's profile
    public function createBuyerProfile($data = NULL){

        $firstName = $this->sanitize($data['firstName']) ?? '';
        $lastName = $this->sanitize($data['lastName']) ?? '';
        $email = $this->sanitize($data['email']) ?? '';
        $companyName = $this->sanitize($data['companyName']) ?? '';
        $pass = $this->sanitize($data['confirmPass']) ?? '';

        $salt = bin2hex(random_bytes(7));

        $sql = 'INSERT INTO 
                    users(
                        full_name,
                        email, 
                        user_type,
                        salt,
                        password
                    ) 
                VALUES(?, ?, ?, ?, ?)';
        $query = $this->conn->prepare($sql);

        $result = $query->execute([
            $firstName. ' ' . $lastName,
            $email,
            'buyer',
            $salt,
            md5($pass.$salt.$this->pepper)
        ]);

        if(!$result){
            $this->log->error('createBuyerProfile insert user data section returned false');
            return -1;
        }
        
        $userId = $this->conn->lastInsertId();
        // inserting company name
        $sql2 = 'INSERT INTO 
                    company(
                        user_id,
                        name
                    ) 
                VALUES(?, ?)';
        $query2 = $this->conn->prepare($sql2);

        $result2 = $query2->execute([
            $userId,
            $companyName
        ]);

        if(!$result){
            $this->log->error('createBuyerProfile insert company name returned false');
            return 0;
        }

        return $userId;
    }
    //gets the salt of the password for the respective email address provided
    public function getUserSalt($email = null){
        
        $email = $this->sanitize($email);

        $sql = 'SELECT salt FROM users u WHERE u.email = ? AND status = 1';
        $query = $this->conn->prepare($sql);
        $query->execute([$email]);

        if ($query->rowCount() > 0){
            return $query->fetch();
        }
        $this->log->info('User salt was not found for the email: '.$email);
        return false;


    }
    //Gets all the companies in the system
    public function getBuyerList(){
        
        $sql = 'SELECT 
                    u.id,
                    u.full_name,
                    c.id, 
                    c.user_id, 
                    c.name as company_name, 
                    c.description, 
                    c.website_link, 
                    c.tax_identification_num, 
                    c.phone, 
                    c.email,
                    c.ctv, 
                    c.street, 
                    c.district,
                    c.is_featured
                FROM 
                    users as u,
                    company as c 
                WHERE 
                    c.user_id = u.id AND
                    u.user_type = "buyer" AND
                    u.status = 1 AND
                    c.status = 1';
        $query = $this->conn->prepare($sql);
        $query->execute();
        
        if ($query->rowCount() > 0 ){
            return $query->fetchAll();
        }
        return false;
    }
    //Gets all the companies in the system
    public function getCompanyList(){
        
        $sql = 'SELECT 
                    c.id, 
                    c.user_id, 
                    c.name, 
                    c.description, 
                    c.website_link, 
                    c.tax_identification_num, 
                    c.phone, 
                    c.email,
                    c.ctv, 
                    c.street, 
                    c.district,
                    c.is_featured,
                    c.logo_img_path as logo_img
                FROM 
                    users as u,
                    company as c 
                WHERE 
                    c.user_id = u.id AND
                    u.user_type = "company" AND
                    u.status = 1 AND
                    c.status = 1';
        $query = $this->conn->prepare($sql);
        $query->execute();
        
        if ($query->rowCount() > 0 ){
            return $query->fetchAll();
        }
        return false;
    }
    //gets the company info by comapnyId or by user_id 
    public function getCompanyDetails($companyId = null, $status = 1){

        $id = 0;

        if (isset($companyId) && $companyId != null){
            //comapny_id passed so find by company_id
	    $id = $this->sanitize($companyId);

            $sql = 'SELECT * FROM company WHERE id = ? AND status = ?';

        }else{
            //Company_id was not passed, so find company_details by user_id in session

	    $id = $_SESSION['USERDATA']['user_id'];
            $sql = 'SELECT * FROM company WHERE user_id = ? AND status = ?';
        }

        $query = $this->conn->prepare($sql);

	$query->bindParam(1, $id);
	$query->bindParam(2, $status);

        if ($query->execute()){

            return $query->fetchAll();
		
	}
        
	$this->log->error('getCompanyDetails failed to execute');
        return false;
    }
    //gets the products for a specifice company
    public function getCompanyProducts($companyId = null){
        
        $companyId = $this->sanitize($companyId);
        $this->status = false;

        try {
            $sql = 'SELECT 
                        prod.id as product_id, 
                        prod.company_id,
                        prod.hs_code,
                        prod.name as product_name,
                        prod.description as product_description,
                        com.name as company_name,
                        sec.id as sector_id,
                        sec.name as sector_name
                    FROM 
                        products AS prod, 
                        company AS com,
                        sector AS sec
                    WHERE 
                        prod.sector_id = sec.id  and
                        prod.company_id = com.id and 
                        prod.status = 1 and 
                        com.status = 1 and 
                        sec.status = 1 and 
                        company_id = '.$companyId.'
            ';

            $query = $this->conn->prepare($sql);
            $query->execute();
            
            if ($query->rowCount() > 0 ){
                $this->status = true;
                $result['products'] = $query->fetchAll();
                return $result['products'];
                
            }
            return $this->status;

        } catch(PDOException $e) {
            $this->log->error('getCompanyProducts: '.$e->getMessage());
            return $this->status;
        }
    }
    //gets all the products providing a filter options
    public function get_product_list($pProductName = null, $pHsCode = null,  $pSectorId = null, $pExportMarketId = null, $pLimit = 0, $pStatus = 1){
        

	//sanitizing 
	$productName 	= $this->sanitize($pProductName);
	$hsCode 	= $this->sanitize($pHsCode);
	$sectorId 	= $this->sanitize($pSectorId);
	$exportMarketId	= $this->sanitize($pExportMarketId);
	$limit  	= $this->sanitize($pLimit);
	$status  	= $this->sanitize($pStatus);

        $sql = '
		call get_product_list(?, ?, ?, ?, ?, ?);
        ';

        $query = $this->conn->prepare($sql);

	$query->bindParam(1, $productName);
	$query->bindParam(2, $hsCode);
	$query->bindParam(3, $sectorId);
	$query->bindParam(4, $exportMarketId);
	$query->bindParam(5, $limit);
	$query->bindParam(6, $status);

        if( $query->execute() ){

		$result['products'] = $query->fetchall();

		foreach ($result['products'] as $key => $val){

			$result['products'][$key]['productimages'] = $this->getproductimages($val['product_id']);
		
		}
		
		return $result['products'];
	
	}
        
	$this->log->error('getproducts() failed to execute');
        return false;

    }
    //gets all the products that are active in the database
    public function getproducts(){
        
        $this->status = false;

        $sql = '
		select 
                    prod.id as product_id, 
                    prod.company_id,
                    prod.hs_code,
                    prod.is_featured,
                    prod.name as product_name,
                    prod.description as product_description,
                    com.name as company_name,
                    sec.id as sector_id,
                    sec.name as sector_name
                from 
                    products as prod,
                    company as com,
                    sector as sec
                where 
                    prod.sector_id = sec.id  and
                    prod.company_id = com.id and
                    prod.status = 1 and 
                    com.status = 1 and 
                    sec.status = 1 
        ';

        $query = $this->conn->prepare($sql);

        if( $query->execute() ){

		$result['products'] = $query->fetchall();

		foreach ($result['products'] as $key => $val){

			$result['products'][$key]['productImages'] = $this->getproductimages($val['product_id']);
		
		}
		
		return $result['products'];
	
	}
        
	$this->log->error('getproducts() failed to execute');
        return false;

    }
    //gets all the products that are export to a particular market
    public function getProductsByExportMarket( $exId ){
        
        $this->status = false;

        $sql = '
		SELECT
			prod.id as product_id,
			prod.company_id,
			prod.hs_code,
			prod.is_featured,
			prod.name as product_name,
			prod.description as product_description,
			com.name as company_name,
			sec.id as sector_id,
			sec.name as sector_name,
		    	eml.export_market_id as export_market_id
		FROM
			products AS prod,
			company AS com,
			sector AS sec,
		        export_market_list AS eml
		WHERE
			prod.sector_id = sec.id  AND
			prod.company_id = com.id AND
			prod.company_id = eml.company_id AND 
		    	eml.export_market_id = ? AND 
		    	eml.status = 1 AND
		    	prod.status = 1 AND
			com.status = 1 AND
			sec.status = 1
        ';

        $query = $this->conn->prepare($sql);
	$query->bindParam(1, $exId);

        if( $query->execute() ){

		$result['products'] = $query->fetchAll();

		foreach ($result['products'] as $key => $val){

			$result['products'][$key]['productImages'] = $this->getProductImages($val['product_id']);
		
		}
		
		return $result['products'];
	
	}
        
	$this->log->error('getProductsByExportMarket() failed to execute');
        return false;

    }
    //gets the company product detail by company and product id. 
    public function getCompanyProductDetail($companyId = null, $productId = null){
        
        $comapnyId = $this->sanitize($companyId);
        $productId = $this->sanitize($productId);
        $this->status = false;

        try {
            $sql = 'SELECT 
                        prod.id AS product_id, 
                        prod.company_id,
                        com.name AS company_name,
                        prod.hs_code,
                        prod.name AS product_name,
                        prod.description AS product_description,
                        sec.id AS sector_id,
                        sec.name AS sector_name
                    FROM 
                        products AS prod, 
                        sector AS sec,
                        company AS com
                    WHERE 
                        prod.sector_id = sec.id  AND
                        prod.company_id = com.id AND
                        prod.status = 1 AND 
                        sec.status = 1 AND 
                        company_id = '.$companyId.' AND
                        prod.id = '.$productId.'
            ';

            $query = $this->conn->prepare($sql);
            
            if ($query->execute() ){

                $this->status = true;
                $result['product'] = $query->fetchAll();

		foreach ($result['product'] as $key => $val){

			$result['product'][$key]['productImages'] = $this->getProductImages($val['product_id']);
		}


                /*foreach($result['product'] as $key => $product){

                    $sql = 'select 
                                id, product_id, file_name, path, size, type 
                            from 
                                product_image
                            where
                                product_id = '.$product['product_id'].' and 
                                status = 1;   
                    ';
                    
                    $query = $this->conn->prepare($sql);
                    $query->execute();

                    $result['product'][$key]['productImages'] = $query->fetchAll();
                }*/
                return $result['product'];
            }
		
            $this->log->error('getCompanyProductDetails failed to execute');
            return false;
	

        } catch(PDOException $e) {
            $this->log->error('getCompanyProductsDetail: '.$e->getMessage());
            return $this->status;
        }
    }   
    //gets all the images for a product by Id and status
    //default image status 1 means active image 0 meaning no longer being used - terminated
    public function getProductImages($pId, $status = 1){

		
	$sql = $this->conn->prepare('
	    SELECT 
		id, product_id, file_name, path, size, type 
	    FROM
		product_image
	    WHERE
		product_id = ? and 
		status = 1;   
	');

	$sql->bindParam(1, $pId);

	if($sql->execute()){

	    return $sql->fetchAll();

	}

	$this->log->error('Failed to execute getProductImages');
	return -1;

    }
    //Gets a product by ID
    public function getProductById($productId){
        
        $productId = $this->sanitize($productId);

        $sql = $this->conn->prepare('

		SELECT 
                    prod.id AS product_id, 
                    prod.company_id ,
                    prod.name AS product_name,
                    prod.hs_code,
                    prod.description AS product_description,
                    sec.id AS sector_id,
                    sec.name AS sector_name
                FROM 
                    products AS prod, sector sec
                WHERE 
                    prod.sector_id = sec.id AND 
                    prod.status = 1 AND  
                    sec.status = 1  AND 
                    prod.id = ?

        ');

        $sql->bindParam(1, $productId);

        if($sql->execute()){

		if ($sql->rowCount() > 0 ){
		    return $sql->fetch();
		}
		$this->log->info('No product was found with the provided product Id');
		return false;
	}

	$this->log->error('Failed to execute getProductById');
	return -1;

        
    }
    //Gets the product that has the same name
    public function getProductByName($productName = null){
        
        $productName = $this->sanitize($productName);

        $sql = 'SELECT 
                    prod.id AS product_id, 
                    prod.company_id ,
                    prod.name AS product_name,
                    prod.hs_code,
                    prod.description AS product_description,
                    sec.id AS sector_id,
                    sec.name AS sector_name
                FROM 
                    products AS prod, sector sec
                WHERE 
                    prod.sector_id = sec.id AND 
                    prod.status = 1 AND  
                    sec.status = 1  AND 
                    prod.name = "'.$productName.'"
        ';

        $query = $this->conn->prepare($sql);
        $query->execute();
        
        if ($query->rowCount() > 0 ){
            return $query->fetchAll();
        }
        return false;
    }
    //Gets all products by HS Code
    public function getProductsByHsCode($hsCode = null){
        
        $hsCode = $this->sanitize($hsCode);

        $sql = 'SELECT 
                    prod.id AS product_id, 
                    prod.company_id ,
                    prod.name AS product_name,
                    prod.hs_code,
                    prod.description AS product_description,
                    sec.id AS sector_id,
                    sec.name AS sector_name
                FROM 
                    products AS prod, sector sec
                WHERE 
                    prod.sector_id = sec.id AND 
                    prod.status = 1 AND  
                    sec.status = 1  AND 
                    prod.hs_code = "'.$hsCode.'"
        ';

        $query = $this->conn->prepare($sql);
        $query->execute();
        
        if ($query->rowCount() > 0 ){
            return $query->fetchAll();
        }
        return false;
    }
    //gets the company info for a particular user
    public function getProductsBySector($sId){
        
	$products = $this->getProducts();
	$filtered = [];

	//getting all products in a sector
	foreach ($products as $key => $val){
	
		if ($val['sector_id'] == $sId){
			array_push($filtered, $products[$key]);
		}
	}

	//getting product images
	/*foreach ($filtered as $key => $val){

		$filtered[$key]['productImages'] = $this->getProductImages($val['product_id']);
	}*/

	return $filtered;

    }
    //gets the user information
    Public function getUserDetails($userId = null){

        $userId = $this->sanitize($userId);
        
        $sql = 'SELECT * FROM users WHERE user_id = '.$userId.' AND status = 1';
        $query = $this->conn->prepare($sql);
        $query->execute();
        
        if ($query->rowCount() > 0 ){
            return $query->fetchAll();
        }
        return false;

    }
    //gets the social media links set by a company
    public function getSocialContactList($companyId = null){
        
        $sql = 'SELECT id, social_contact_id, company_id, link FROM social_contact_list WHERE company_id = '.$companyId.' AND status = 1';
        $query = $this->conn->prepare($sql);
        $query->execute();
        
        if ($query->rowCount() > 0 ){
            return $query->fetchAll();
        }
        return false;
    }
    //gets all the social media that a company can set
    public function getSocialContact(){
        
        $sql = 'SELECT id, name, icon FROM social_contacts WHERE status = 1';
        $query = $this->conn->prepare($sql);
        $query->execute();
        
        if ($query->rowCount() > 0 ){
            return $query->fetchAll();
        }
        return false;
    }
    //gets a list of all the export markets pertaining to a company
    public function getExportMarketList($companyId = null){

        $sql = 'SELECT eml.id, eml.export_market_id , em.name
                FROM export_market_list as eml, export_market as em 
                WHERE eml.company_id = '.$companyId.' AND eml.export_market_id = em.id AND eml.status = 1;';
        $query = $this->conn->prepare($sql);
        $query->execute();
        
        if ($query->rowCount() > 0 ){
            return $query->fetchAll();
        }
        return false;

    }
    // Gets all export markets
    public function getExportMarkets(){
        
        $sql = 'SELECT id, name FROM export_market WHERE status = 1 ORDER BY name ASC;';
        $query = $this->conn->prepare($sql);
        
        if ($query->execute()){
            return $query->fetchAll();
        }
        return false;

    }
    //gets an export market by Id
    public function getExportMarketById($exId){
        
	$exMarkets = $this->getExportMarkets();

	foreach ($exMarkets as $key => $val){

		if ($val['id'] == $exId){
			
			return $val;

		}
	}

	return [];
	
    }
    // Gets all interest for a user 
    public function getInterest(){
        
        $sql = 'SELECT 
                    i.id, sector_id, s.name 
                FROM 
                    interest AS i, sector AS s 
                WHERE 
                    i.user_id = '.$_SESSION['USERDATA']['user_id'].' AND
                    i.sector_id = s.id AND 
                    i.status = 1 AND
                    s.status = 1;
                    ';
        $query = $this->conn->prepare($sql);
        $query->execute();
        
        if ($query->rowCount() > 0 ){
            return $query->fetchAll();
        }
        $this->log->info('getInterest returned false, no interest were found');
        return false;

    }
    // Gets all sectors
    public function getUserPages($userType = null){
        
        $sql = 'SELECT 
                    id, display_name, icon, link FROM page
                WHERE 
                    user_access_type = "ALL" OR 
                    user_access_type = "'.$userType.'"
                ORDER BY display_name ASC;';

        $query = $this->conn->prepare($sql);
        $query->execute();
        
        if ($query->rowCount() > 0 ){
            return $query->fetchAll();
        }
        $this->log->info('getSectors returned false, no sectors found');
        return false;

    }
    // Gets all sectors
    public function getSectors(){
        
        $sql = $this->conn->prepare('SELECT id, name, is_featured, img_path as sector_img FROM sector WHERE status = 1 ORDER BY name ASC;');
        
        $sql->bindParam(1, $productId);

        if($sql->execute()){

		if ($sql->rowCount() > 0 ){
		    return $sql->fetchAll();
		}
		$this->log->info('No Sectors were found');
		return false;
	}

	$this->log->error('getSectors returned false, no sectors found');
	return -1;
    }
    //gets a sector by Id
    public function getSectorById($sId){
       
	$sectors = $this->getSectors();

	foreach ($sectors as $key => $val){
	
		if ($sId == $val['id']){
			return $sectors[$key];
		}
	}

	return array();
	 
	

    }
    // Updates or inserts a user's social constact list
    public function setSocialContactList ($data = null){

        foreach ($data as $key => $array){
            
            $link = $this->sanitize($array['link']) ?? null;
            
            if ($array['socialContactListId'] > 0){
                //record exist -- update
                $sql = "UPDATE 
                            social_contact_list 
                        SET 
                            social_contact_id = ?,
                            link = ?

                        WHERE 
                            id = ".$array['socialContactListId']."";
                
                $query = $this->conn->prepare($sql);
                $result = $query->execute([
                    $array['socialContactId'],
                    $link
                ]);
                
                if (!$result){
                    $this->log->error('setSocialContactist update section returned false');
                    return false;
                }

            }else{
                //record doesnt exist -- insert
                $sql = 'INSERT INTO 
                            social_contact_list(
                                social_contact_id,
                                company_id, 
                                link
                            ) 
                        VALUES(?, ?, ? )';
                $query = $this->conn->prepare($sql);

                $result = $query->execute([
                    $array['socialContactId'],
                    $array['companyId'],
                    $link
                ]);

                if(!$result){
                    $this->log->error('setSocialContactist insert section returned false');
                    return false;
                }

            }
        }
        return true;

    }
    // Updates or Inserts intereset based on data recieved
    public function setInterestList($data  = null, $userId = null){

        try {
            $this->conn->beginTransaction();
            foreach ($data as $array){
                
                $status = 1;
                if ($array['sectorId'] == 0){
                    $status = 0;
                }

                if (isset($array['interestId']) && $status != 0){
                    //record exist  --- update
                    $sql = "UPDATE 
                                interest 
                            SET 
                                ".(($status == 1 )? 'sector_id = ?,' : '')."
                                status = ?
    
                            WHERE 
                                id = ".$array['interestId']." AND 
                                user_id = ".$userId."";
                    
                    $query = $this->conn->prepare($sql);
                    $result = $query->execute([$array['sectorId'], $status]);
                    
                    if (!$result){
                        $this->log->error('setExportMarketList update section returned false');
                        return false;
                    }
    
                }else if (isset($array['interestId']) && $status == 0){

                    // remove record
                    $sql = "UPDATE 
                                interest 
                            SET 
                                
                                status = 0
    
                            WHERE 
                                id = ".$array['interestId']." AND 
                                user_id = ".$userId."";
                    
                    $query = $this->conn->prepare($sql);
                    $result = $query->execute();
                    
                    if (!$result){
                        $this->log->error('setExportMarketList update section returned false');
                        return false;
                    }

                }else{
                    if ($status != 0){
                        //record doesn't exist --- insert
                        $sql = 'INSERT INTO 
                                    interest(
                                        sector_id,
                                        user_id 
                                    ) 
                                VALUES(?, ?)';
                        $query = $this->conn->prepare($sql);
        
                        $result = $query->execute([
                            $array['sectorId'],
                            $userId
                        ]);
        
                        if(!$result){
                            $this->log->error('setExportMarketList insert section returned false');
                            return false;
                        }

                    }
                }
            }
            $this->conn->commit();
            return true;

        }catch (PDOException $e){
            $this->conn->rollback();
            $this->log->error('setInterestList was rolledback due to an error: '.$e->getMessage());
            return false;
        }
      

    }
    // Updates or Inserts product information
    public function setProductDetails($data = null){

        $companyId = $_SESSION['COMPANYDATA'][0]['id'];
        $name = $this->sanitize($data['prodName']) ?? null;
        $description = $this->sanitize($data['productDescription']) ?? null;
        $sectorId = $this->sanitize($data['sectorId']) ?? null;
        $hs_code = $this->sanitize($data['hs_code']) ?? null;
        
        if (isset($data['productId'])){
            //record exist  --- update
            $sql = "UPDATE 
                        products 
                    SET 
                        name = ?,
                        description = ?,
                        sector_id = ?,
                        hs_code = ?

                    WHERE 
                        id = ".$data['productId']." AND 
                        company_id = ".$companyId."
            ";
            
            $query = $this->conn->prepare($sql);
            $result = $query->execute([
                $name,
                $description,
                $sectorId,
                $hs_code

            ]);
            
            if (!$result){
                $this->log->error('setProductDetails UPDATE section returned false');
                return false;
            }
        }else{
            //record doesn't exist --- Insert

            $sql = 'INSERT INTO 
                        products(
                            company_id,
                            name,
                            description,
                            sector_id,
                            hs_code 
                        ) 
                    VALUES(?, ?, ?, ?, ?)
                    ';
            $query = $this->conn->prepare($sql);

            $result = $query->execute([
                $companyId,
                $name,
                $description,
                $sectorId,
                $hs_code
            ]);

            if(!$result){
                $this->log->error('setProductDetails INSERT section returned false');
                return false;
            }

            return $this->conn->lastInsertId();
        }
        return true; 
    }
    // Inserts or updates a product image
    public function setProductImages($data = null){
        
        if (isset($data['productImageId'])){
            //Record exists --- UPDATE --- removing the product
            $sql = "UPDATE 
                        product_image
                    SET 
                        status = ?

                    WHERE 
                        product_id = ".$data['productId']." AND
                        id = ".$data['productImageId']."";
                        
            
            $query = $this->conn->prepare($sql);
            $result = $query->execute([
                $data['status']
            ]);
            
            if (!$result){
                $this->log->error('setProductImage UPDATE section returned false');
                return false;
            }

            return $data['productImageId'];

        }else{
            //Record doesnt exist -- INSERT
            try {
                $this->conn->beginTransaction();

                $filePaths = $this->helper->uploadImage($data,'uploads/products/');
                $fileCount = count($filePaths);

                for ($i = 0; $i < $fileCount; $i++){

                    $sql = 'INSERT INTO 
                                product_image(
                                    product_id,
                                    file_name, 
                                    path,
                                    size,
                                    type 
                                ) 
                            VALUES(?, ?, ?, ?, ?)';

                    $query = $this->conn->prepare($sql);

                    $result = $query->execute([
                        $data['productId'],
                        $filePaths[$i]['file_name'],
                        $filePaths[$i]['file_path'],
                        $filePaths[$i]['size'],
                        $filePaths[$i]['type']

                    ]);

                    if(!$result){
                        $this->log->error('setProductImage INSERT section returned false');
                        throw new PDOException;
                    }
                    
                }
                
                $this->conn->commit();
                return true;
            
            }catch (PDOException $e){
                $this->conn->rollback();
                $this->log->error('setProductImages was rolledback due to an error: '.$e->getMessage());
                return false;
            }
        }

    }
    // Updates or Inserts export markets for a user 
    public function setExportMarketList($data = null){

        foreach ($data as $array){

            if (isset($array['exportMarketListId'])){
                //record exist  --- update
                $sql = "UPDATE 
                            export_market_list 
                        SET 
                            export_market_id = ?

                        WHERE 
                            id = ".$array['exportMarketListId']."";
                
                $query = $this->conn->prepare($sql);
                $result = $query->execute([
                    $array['exportMarketId']
                ]);
                
                if (!$result){
                    $this->log->error('setExportMarketList update section returned false');
                    return false;
                }

            }else{
                //record doesn't exist --- insert
                $sql = 'INSERT INTO 
                            export_market_list(
                                company_id,
                                export_market_id 
                            ) 
                        VALUES(?, ?)';
                $query = $this->conn->prepare($sql);

                $result = $query->execute([
                    $array['companyId'],
                    $array['exportMarketId']
                ]);

                if(!$result){
                    $this->log->error('setExportMarketList insert section returned false');
                    return false;
                }

            }

        }
        return true;
    }
    // Removes an export market from export market list (For a Company)
    public function removeExportMarketFromList($data = null){
        
        $sql = "UPDATE 
                    export_market_list 
                SET 
                    status = 0
                WHERE 
                    id = ".$data['id']."";
        
        $query = $this->conn->prepare($sql);
        $result = $query->execute();
        
        if (!$result){
            $this->log->error('removeExportMarketFromList returned false');
            return false;
        }
        return true;


    }
    public function removeProduct($productId = null){
        
        $sql = "UPDATE 
                    products 
                SET 
                    status = 0
                WHERE 
                    id = ".$productId." AND 
                    company_id = ".$_SESSION['COMPANYDATA'][0]['id']."                    
                    ";
        
        $query = $this->conn->prepare($sql);
        $result = $query->execute();
        
        if (!$result){
            $this->log->error('removeProduct Returned false, product_Id: '.$productId.', userId: '.$_SESSION['USERDATA'][0]['user_id']);
            return false;
        }
        return true;



    }
    // Updates the user profile
    public function updateUserProfile($data = null){

        $fName = $this->sanitize($data['firstName']) ?? '';
        $lName = $this->sanitize($data['lastName']) ?? '';

        $sql = 'UPDATE users SET full_name = ? WHERE id = '.$_SESSION['USERDATA']['user_id'].';';
        $query = $this->conn->prepare($sql);
        $result = $query->execute([$fName.' '.$lName]);
        
        if (!$result){
            $this->log->error('updateUserProfile returned false');
            return false;
        }
        $_SESSION['USERDATA']['full_name'] = $fName.' '.$lName;
        return true;
        

    }
    // Updates a companys profile 
    public function updateCompanyProfile($data = null){
        
        $ctv = $this->sanitize($data['ctv'] ?? NULL);
        $description = $this->sanitize($data['description'] ?? NULL);
        $name = $this->sanitize(($data['name'] ?? NULL)) ;
        $email = $this->sanitize($data['email'] ?? NULL);
        $phone = $this->sanitize($data['phone'] ?? NULL);
        $street = $this->sanitize($data['street'] ?? NULL);
        $website = $this->sanitize($data['website'] ?? NULL);
        $district = $this->sanitize($data['district'] ?? NULL);
        
        $sql = "UPDATE 
                    company 
                SET 
                    name = ?,
                    description = ?,
                    website_link = ?,
                    phone = ?,
                    email = ?,
                    ctv = ?,
                    street = ?,
                    district = ?,
                    logo_img_path = ?
                WHERE 
                    id = ".$data['companyId']." AND
                    user_id = ".$_SESSION['USERDATA']['user_id']."";

        $query = $this->conn->prepare($sql);
        $result = $query->execute([
            $name,
            $description,
            $website,
            $phone,
            $email,
            $ctv,
            $street,
            $district,
            ($data['logoImagePath'] ?? NULL)
        ]);
        
        if (!$result){
            $this->log->error('updateComanyProfile returned false');
            return false;
        }
        return true;


    }
    

    /*
    *   Functions below are used to help the process class in carring out additional functionality.
    *   Functions below do not communicate with the database.
    */

    //applies a basic sanitization
    public function sanitize($input){
        if ($input == null) {
                return null;
        }

        $input = trim($input);

        $search = array(
          '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
          '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
          '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
          '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
        );

        $output = preg_replace($search, '', $input);
        return $output;
    }

}

?>
