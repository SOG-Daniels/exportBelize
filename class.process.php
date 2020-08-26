<?php 
// The process class handles all transactions to the database

require_once('./definitions.php');
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
    public function sanitize($valu = null){

        $value = trim($valu);
        $value = htmlspecialchars($value);
        $value = stripcslashes($value);

        return $value;
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

    public function validateLogin ($data = null){

        $email = $this->sanitize($data['email']) ?? '';
        $pass = $data['password'] ?? '';

        $result = $this->getUserSalt($email);

        if ($result != false ){
            
            $pass = md5($pass.$result['salt'].$this->pepper);
            
            //salt was returned
            $sql = 'SELECT id, full_name, email, user_type FROM users u WHERE u.email = ? AND u.password = ?';
            $query = $this->conn->prepare($sql);
            $query->execute([$email, $pass]);

            if ($query->rowCount() > 0 ){
                return $query->fetch();
            }
            $this->log->info('User: '.$email.' Failed login attempt');
        }
        return false;

    }
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
            $this->log->error('createBuyerProfile insert section returned false');
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
        // try {
        //     $pdo->beginTransaction();
        //     foreach ($data as $row)
        //     {
        //         $stmt->execute($row);
        //     }
        //     $pdo->commit();
        // }catch (Exception $e){
        //     $pdo->rollback();
        //     throw $e;
        // }
    }
    //gets the company info for a particular user
    public function getCompanyDetails($userId = null){
        
        $sql = 'SELECT * FROM company WHERE user_id = '.$userId.' AND status = 1';
        $query = $this->conn->prepare($sql);
        $query->execute();
        
        if ($query->rowCount() > 0 ){
            return $query->fetchAll();
        }
        return false;
    }
    //gets the user information
    Public function getUserDetails($userId = null){
        
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
    //gets all export markets
    public function getExportMarkets(){
        
        $sql = 'SELECT id, name FROM export_market WHERE status = 1 ORDER BY name ASC;';
        $query = $this->conn->prepare($sql);
        $query->execute();
        
        if ($query->rowCount() > 0 ){
            return $query->fetchAll();
        }
        return false;

    }
    // Gets all interest for a user 
    public function getInterest(){
        
        $sql = 'SELECT 
                    i.id, sector_id, s.name 
                FROM 
                    interest AS i, sector AS s 
                WHERE 
                    i.user_id = '.$_SESSION['user_id'].' AND
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
    public function getSectors(){
        
        $sql = 'SELECT id, name FROM sector WHERE status = 1 ORDER BY name ASC;';
        $query = $this->conn->prepare($sql);
        $query->execute();
        
        if ($query->rowCount() > 0 ){
            return $query->fetchAll();
        }
        $this->log->info('getSectors returned false, no sectors found');
        return false;

    }
    //Updates the user profile
    public function updateUserProfile($data = null){

        $fName = $this->sanitize($data['firstName']) ?? '';
        $lName = $this->sanitize($data['lastName']) ?? '';

        $sql = 'UPDATE users SET full_name = ? WHERE id = '.$_SESSION['user_id'].';';
        $query = $this->conn->prepare($sql);
        $result = $query->execute([$fName.' '.$lName]);
        
        if (!$result){
            $this->log->error('updateUserProfile returned false');
            return false;
        }
        $_SESSION['full_name'] = $fName.' '.$lName;
        return true;
        

    }
    //Updates a companys profile 
    public function updateCompanyProfile($data = null){
        
        $ctv = $this->sanitize($data['ctv']) ?? NULL;
        $description = $this->sanitize($data['description']) ?? NULL;
        $name = $this->sanitize($data['name']) ?? NULL;
        $email = $this->sanitize($data['email']) ?? NULL;
        $phone = $this->sanitize($data['phone']) ?? NULL;
        $street = $this->sanitize($data['street']) ?? NULL;
        $website = $this->sanitize($data['website']) ?? NULL;
        $district = $this->sanitize($data['district']) ?? NULL;
        
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
                    user_id = ".$_SESSION['user_id']."";
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
            $data['logoImagePath']
        ]);
        
        if (!$result){
            $this->log->error('updateComanyProfile returned false');
            return false;
        }
        return true;


    }
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

                if (isset($array['interestId'])){
                    //record exist  --- update
                    
    
                    $sql = "UPDATE 
                                interest 
                            SET 
                                sector_id = ?,
                                status = ?
    
                            WHERE 
                                id = ".$array['interestId']." AND 
                                user_id = ".$userId."";
                    
                    $query = $this->conn->prepare($sql);
                    $result = $query->execute([
                        $array['sectorId'],
                        $status

                    ]);
                    
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
}

?>