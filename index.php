<?php 
    // The Index.php file is the controller of the application, which request data from the DBMS and returns the appropriate view
    require_once('./class.interface.php');
    require_once('./class.process.php');
    require_once('./class.email.php');

    session_start();
        
    // echo "<br><br><br><br>";
    // echo "<pre>";
    // print_r($_SESSION);
    // echo "</pre>";

    $view = new Ui();
    $process = new Process();

    $accountOptions = array();

    $pageContent = '';

    if($_GET){
        // HADLES ALL PAGE REQUEST FOR ALL USERS
        
        if ($_GET['page'] == 'signIn'){

            if (!empty($_SESSION['USERDATA'])){
                // IF USER IS SIGNED IN NO SIGNUP PAGE IS AVAILABLE
               
                if ($_SESSION['USERDATA']['user_type'] == 'company'){

                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['COMPANYDATA'][0]['id']);
                    $result['socialContacts'] = $process->getSocialContact();
                    $result['socialContactList'] = $process->getSocialContactList($result['companyDetails'][0]['id']);
                    $result['exportMarkets'] = $process->getExportMarkets();
                    $result['exportMarketList'] = $process->getExportMarketList($result['companyDetails'][0]['id']);
                
                    $pageContent = $view->companyProfile($result);
                }else if($_SESSION['USERDATA']['user_type'] == 'admin'){
                    
                    $pageContent = $view->adminProfile();

                }else if ($_SESSION['USERDATA']['user_type'] == 'buyer'){

                    $result['sectors'] = $process->getSectors();
                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['COMPANYDATA'][0]['id']);
                    $result['interest'] = $process->getInterest();

                    $pageContent = $view->buyerProfile($result);

                }else{
                    $pageContent = $view->pageNotFound();
                }

            }else{

                $pageContent = $view->signIn();
            
            }

        }else if ($_GET['page'] == 'forgotPassword'){
            
            if (!empty($_SESSION['USERDATA'])){
                // IF USER IS SIGNED IN NO SIGNUP PAGE IS AVAILABLE
               
                if ($_SESSION['USERDATA']['user_type'] == 'company'){

                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['COMPANYDATA'][0]['id']);
                    $result['socialContacts'] = $process->getSocialContact();
                    $result['socialContactList'] = $process->getSocialContactList($result['companyDetails'][0]['id']);
                    $result['exportMarkets'] = $process->getExportMarkets();
                    $result['exportMarketList'] = $process->getExportMarketList($result['companyDetails'][0]['id']);
                
                    $pageContent = $view->companyProfile($result);
                }else if($_SESSION['USERDATA']['user_type'] == 'admin'){
                    
                    $pageContent = $view->adminProfile();

                }else if ($_SESSION['USERDATA']['user_type'] == 'buyer'){

                    $result['sectors'] = $process->getSectors();
                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['COMPANYDATA'][0]['id']);
                    $result['interest'] = $process->getInterest();

                    $pageContent = $view->buyerProfile($result);

                }else{
                    $pageContent = $view->pageNotFound();
                }

            }else{

                $pageContent = $view->forgotPassword();
            
            }


        }else if ($_GET['page'] == 'companyRegistration'){

            $pageContent = $view->companyRegistrationForm();

        }else if ($_GET['page'] == 'buyerRegistration'){
            
            $result['sectors'] = $process->getSectors();

            $pageContent = $view->buyerRegistrationForm($result);

        }else if ($_GET['page'] == 'aboutUs'){    

            $pageContent = $view->aboutUs();

        }else if ($_GET['page'] == 'contact'){    

            $pageContent = $view->contact();

        }else if ($_GET['page'] == 'viewProducts'){    
            
            // $result['products'] = $process->getProductsBySector($_GET['sectorId']);
            $result['sectors'] = $process->getSectors();

            $pageContent = $view->viewProducts($result);
        
        }else if ($_GET['page'] == 'productDetails' && isset($_GET['productId'])){    

            $product = $process->getProductById($_GET['productId']);

            if ($product == false){
                $pageContent = $view->pageNotFound();
            }else{
                $result['exportMarkets'] = $process->getExportMarkets();
                
                $result['exportMarketList'] = $process->getExportMarketList($product['company_id']);
                $result['productDetails'] = $process->getCompanyProductDetail($product['company_id'], $product['product_id']);
                $result['companyDetails'] = $process->getCompanyDetails($product['company_id']);

                $pageContent = $view->productDetails($result);
            }
        
        }else if ($_GET['page'] == 'companyDetail' && isset($_GET['companyId'])){    

            $result['companyDetails'] = $process->getCompanyDetails($_GET['companyId']);

            if ($result['companyDetails'] == false){
                $pageContent = $view->pageNotFound();
            }else{
                
                $breadCrumbs = '';

                if (!empty($_SESSION['USERDATA']) && $_SESSION['USERDATA']['user_type'] == 'admin'){
                    $breadCrumbs = '
                        <li class="breadcrumb-item"><a href="'.BASE_URL.'index.php/?page=companyList">Company List</a></li>
                        <li class="breadcrumb-item text-white" aria-current="page">Company Details</li>
                    ';
                }else{
                    $breadCrumbs = '
                        <li class="breadcrumb-item text-white" aria-current="page">Company Details</li>
                    ';
                }
                $products = $process->getCompanyProducts($result['companyDetails'][0]['id']);
                
                foreach ($products as $key => $product){
                    $productDetail = $process->getCompanyProductDetail($result['companyDetails'][0]['id'], $product['product_id']);
                    $result['products'][$key] = $productDetail[0];
                }

                $result['socialContacts'] = $process->getSocialContact();
                $result['exportMarkets'] = $process->getExportMarkets();
                $result['sectors'] = $process->getSectors();
                
                $result['socialContactList'] = $process->getSocialContactList($result['companyDetails'][0]['id']);
                $result['exportMarketList'] = $process->getExportMarketList($result['companyDetails'][0]['id']);
                $result['breadCrumbs'] = $breadCrumbs;

                $pageContent = $view->companyDetails($result);
            }

        }elseif (!empty($_SESSION['USERDATA']) && $_GET){
            // HANDLES ALL PAGE REQUEST FOR SIGNED IN USERS

            if($_GET['page'] == 'logout'){
                //ENDING USER SESSION

                $_SESSION['USERDATA'] = '';
                $_SESSION['PAGES'] = '';

                unset($_SESSION['USERDATA']);
                unset($_SESSION['PAGES']);
                
                session_destroy();

                $pageContent = $view->home();
                
            }else if($_GET['page'] == 'editProduct' && $_SESSION['USERDATA']['user_type'] != 'buyer'){
                // PAGES ACCESSABLE BY ADMIN AND COMPANY

                if(isset($_GET['productId'])){
                   
                    $companyId = $_SESSION['COMPANYDATA'][0]['id'] ?? $_GET['companyId'] ?? null;
                    $result['companyDetails'] = $process->getCompanyDetails($companyId);

                    if ($result['companyDetails'] == false){

                        $pageContent = $view->pageNotFound();

                    }else{

                        $result['sectors'] = $process->getSectors();
                        $result['product'] = $process->getCompanyProductDetail($result['companyDetails'][0]['id'], $_GET['productId']);
                        
                        if ($result['product'] == false){
                            $pageContent = $view->pageNotFound();
                        }else{

                            $result['initialPrev'] = array();
                            $result['initialPrevConfig'] = array();

                            foreach($result['product'][0]['productImages'] as $key => $productImg){

                                $result['initialPrev'] = array(
                                    BASE_URL.$productImg['path']
                                );
                                $result['initialPrevConfig'][$key] = array(
                                    'caption' => $productImg['file_name'],
                                    'url' => BASE_URL.'index.php/',
                                    'key' => $productImg['id'],
                                    'size' => $productImg['size'],
                                    'downloadUrl' => BASE_URL.$productImg['path'],
                                    'type' => $productImg['type'],
                                    'extra' => ['productId'=>$result['product'][0]['product_id'], 'ajaxRequest'=>'removeProductImg']
                                );

                            }
                            $result['uploadExtraData'] = array(
                                'ajaxRequest' => 'uploadProductImg',
                                'productId' => $result['product'][0]['product_id']
                            );
                           
                            $result['pageTitle'] = $result['product'][0]['product_name'];
                            $result['bannerTitle'] = 'Edit Product';
                            
                            $pageContent = $view->editMyProducts($result);
                        }

                    }

                }else{
                    $pageContent = $view->home();                    
                }
                

            }else if ($_SESSION['USERDATA']['user_type'] == 'company'){
                //ALL PAGES AVAILABLE FOR A COMPANY PROFILE

                if ($_GET['page'] == 'profile'){

                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['COMPANYDATA'][0]['id']);
                    $result['socialContacts'] = $process->getSocialContact();
                    $result['socialContactList'] = $process->getSocialContactList($result['companyDetails'][0]['id']);
                    $result['exportMarkets'] = $process->getExportMarkets();
                    $result['exportMarketList'] = $process->getExportMarketList($result['companyDetails'][0]['id']);
                
                    $pageContent = $view->companyProfile($result);
                    
                }else if ($_GET['page'] == 'viewProducts'){ 
                
                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['COMPANYDATA'][0]['id']);
                    $result['socialContactList'] = $process->getSocialContactList($result['companyDetails'][0]['id']);
                    $result['exportMarketList'] = $process->getExportMarketList($result['companyDetails'][0]['id']);
                    $result['myproducts'] = $process->getCompanyProducts();
    
                    $pageContent = $view->viewProducts();

                }else if ($_GET['page'] == 'myProducts'){ 
                    
                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['COMPANYDATA'][0]['id']);
                    $result['products'] = $process->getCompanyProducts($result['companyDetails'][0]['id']);
                    
                    $pageContent = $view->productList($result);
    
                }else if ($_GET['page'] == 'removeProduct'){

                    if (isset($_GET['productId'])){

                        $result['message'] = '
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong><i class="fa fa-check fa-lg"></i> Success!</strong> Product has been removed!
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        ';
                        $wasUpdated = $process->removeProduct($_GET['productId']);

                        if (!$wasUpdated){
                            $result['message'] = '
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="fa fa-check fa-lg"></i> Sorry!</strong> We couldn\'t find that product.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            ';
                        }

                        $result['companyDetails'] = $process->getCompanyDetails($_SESSION['COMPANYDATA'][0]['id']);
                        $result['products'] = $process->getCompanyProducts($result['companyDetails'][0]['id']);

                        $pageContent = $view->productList($result);

                    }else{

                        $pageContent = $view->pageNotFound();

                    }
                    
                    
                }else if ($_GET['page'] == 'addProduct'){ 
                    
                    $result['sectors'] = $process->getSectors();
                    
                    $pageContent = $view->addProduct($result);
    
                }else{
                    //PAGE REQUEST NOT FOUND
                    $pageContent = $view->home();
                }

            }else if($_SESSION['USERDATA']['user_type'] == 'buyer'){
                //ALL PAGES A BUYER ACCOUNT CAN ACCCESS

                if ($_GET['page'] == 'profile'){
                    
                    $result['sectors'] = $process->getSectors();
                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['COMPANYDATA'][0]['id']);
                    $result['interest'] = $process->getInterest();

                    $pageContent = $view->buyerProfile($result);

                }else{

                }
            }else if($_SESSION['USERDATA']['user_type'] == 'admin'){
                // ALL PAGES AN ADMIN ACCOUNT CAN ACCESS

                if ($_GET['page'] == 'profile'){
                    $pageContent = $view->adminProfile();
                }else if ($_GET['page'] == 'productList'){

                    if (isset($_GET['companyId'])){
                        //display products for a specific company

                        $result['products'] = $process->getCompanyProducts($_GET['companyId']);

                        if ($result['products'] == false){
                            //company products not found

                            $pageContent = $view->pageNotFound();

                        }else{
                            //company products found 

                            $result['companyDetails'] = $process->getCompanyDetails($_GET['companyId']);
                            $result['bannerTitle'] = $result['companyDetails'][0]['name'].' Product List';
                            $result['pageTitle'] = $result['companyDetails'][0]['name'].' Products';
                            $result['breadCrumb'] ='
                            <li class="breadcrumb-item"><a href="'.BASE_URL.'index.php/?page=companyList">Company List</a></li>
                            <li class="breadcrumb-item text-white" aria-current="page">Product List</li>
                            ';

                            $pageContent = $view->productList($result);

                        }

                    }else{
                        //display all products

                        $result['products'] = $process->getProducts();
                        $result['bannerTitle'] = 'Product List';
                        $result['pageTitle'] = 'Products';
                        $result['breadCrumb'] ='
                        <li class="breadcrumb-item text-white" aria-current="page">Product List</li>
                        ';
                        
                        $pageContent = $view->productList($result);


                    }
                
                }else if ($_GET['page'] == 'editCompany'){

                    if (isset($_GET['companyId'])){

                        $result['companyDetails'] = $process->getCompanyDetails($_GET['companyId']);
                        $result['sector'] = $process->getSectors();

                        if ($result['companyDetails'] == false){
                            $pageContent = $view->pageNotFound();
                        }else{
                            
                            //Getting Company Profile
                            $result['products'] = $process->getCompanyProducts($result['companyDetails'][0]['id']);
                            $result['socialContacts'] = $process->getSocialContact();
                            $result['socialContactList'] = $process->getSocialContactList($result['companyDetails'][0]['id']);
                            $result['exportMarkets'] = $process->getExportMarkets();
                            $result['exportMarketList'] = $process->getExportMarketList($result['companyDetails'][0]['id']);
                            
                            $pageContent = $view->editCompanyDetails($result);
                        }

                    }else{
                        $pageContent = $view->home();
                    }

                }else if ($_GET['page'] == 'companyList'){

                    $result['companyList'] = $process->getCompanyList();

                    //getting the amout of products a comapany has
                    foreach ($result['companyList'] as $key => $company){
                        $result['companyList'][0]['productCount'] = count($process->getCompanyProducts($company['id']));
                    }

                    $pageContent = $view->companyList($result);
                
                }else{
                    $pageContent = $view->pageNotFound();
                }

            }else{
                //ACCESSING AN PAGE THAT IS NOT AVAILABLE FOR THE ACCOUNT TYPE
                $pageContent = $view->pageNotFound();
            }
        }else{
            //PAGE REQUEST NOT FOUND
            $result['sectors'] = $process->getSectors();
            $result['products'] = $process->getProducts();

            $pageContent = $view->home($result);
        }

    }else if (!empty($_POST) && isset($_POST['action'])){
        // HANDLES ALL USER ACTIONS VIA POST

        if ($_POST['action'] == 'signIn' && empty($_SESSION['USERDATA'])){

            $result = $process->validateLogin($_POST);
            
            if ($result == false){
                $message = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Email or Password are not valid!</strong> Please check your email or password and try again.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                ';
                $pageContent = $view->signIn($message);
            }else{
                //setting sessions
                
                $_SESSION['USERDATA'] = $result;
                $_SESSION['COMPANYDATA'] = $process->getCompanyDetails();
                $_SESSION['PAGES'] = $process->getUserPages($_SESSION['USERDATA']['user_type']); 

                if ($_SESSION['USERDATA']['user_type'] == 'admin'){
                    $pageContent = $view->adminProfile();
                }else if($_SESSION['USERDATA']['user_type'] == 'company'){
                   
                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['COMPANYDATA'][0]['id']);
                    $result['socialContacts'] = $process->getSocialContact();
                    $result['socialContactList'] = $process->getSocialContactList($result['companyDetails'][0]['id']);
                    $result['exportMarkets'] = $process->getExportMarkets();
                    $result['exportMarketList'] = $process->getExportMarketList($result['companyDetails'][0]['id']);
                    
                    $pageContent = $view->companyProfile($result);
                }else{
                    
                    $result['sectors'] = $process->getSectors();
                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['COMPANYDATA'][0]['id']);
                    $result['interest'] = $process->getInterest();

                    $pageContent = $view->buyerProfile($result);
                }
                
            }

        }else if($_POST['action'] == 'productSearch'){

            $products = [];
            $result['sectorId'] = $_POST['sectorId'];
            $result['productNameSearch'] = $_POST['productName'];
            $result['sectors'] = $process->getSectors();

            //filtering out products for output
            if (isset($_POST['sectorId']) && $_POST['sectorId'] != 0){
                $products = $process->getProductsBySector($_POST['sectorId']);
            }
            if (isset($_POST['hsCode']) && $_POST['hsCode'] != null){

                if (!empty($products)){
                    // apply filter to products array
                    $productsWithHsCode = [];

                    foreach ($products as $key => $product){
                        if ($product['hs_code'] == $_POST['hsCode']){
                            $productsWithHsCode[] = $products[$key];
                        }
                    }
                    $products = $productsWithHsCode;
                }else{
                    $product = $process->getProductsByHsCode($_POST['hsCode']);
                }

            }
            if (isset($_POST['productName']) && $_POST['productName'] != null){
                
                if (!empty($products)){
                    // apply filter to products array
                    $productsWithFilterName = [];

                    foreach ($products as $key => $product){
                        if ($product['product_name'] == $_POST['productName']){
                            $productsWithfilterName[] = $products[$key];
                        }
                    }
                    $products = $productsWithfilterName;
                }else{
                    $products = $process->getProductByName($_POST['productName']);
                }
            }

            if (!empty($products)){
                //getting product details to display on view
                foreach ($products as $key => $product){
                    $prodDetail = $process->getCompanyProductDetail($product['company_id'], $product['product_id']);
                    $result['products'][] = $prodDetail[0];
                }
            }

            $pageContent = $view->viewProducts($result);

        }else if($_POST['action'] == 'buyerRegistration'){
            $userExist = $process->getUserSalt($_POST['email']);
            $result['sectors'] = $process->getSectors();

            if ($userExist == false){
                if ($_POST['newPass'] != $_POST['confirmPass']){
                    $result['message'] = '
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Notice:</strong> Please make sure both passwords are entered correctly.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    ';
                }else{
                    
                    $result['message'] = '
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> Account has been created please sign in.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                    ';

                    //return buyer Id upon success
                    $createdBuyer = $process->createBuyerProfile($_POST);
                    
                    if ($createdBuyer != false){

                        $addedInterest = $process->setInterestList($_POST['interest'], $createdBuyer);

                        if ($addedInterest == false){

                            $result['message'] .= '
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Notice:</strong> Unable to save selected interest.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            ';
                        }

                    }else{ 
                        $result['message'] = '
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Notice:</strong> Unable to create account at the moment.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                        ';
                    }


                }
            }else{
                $result['message'] = '
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Notice:</strong> Email: "'.$_POST['email'].'" already exist!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                ';
            }
            $pageContent = $view->buyerRegistrationForm($result);
        }else if(!empty($_SESSION['USERDATA']) ){
            //USER IS LOGGED IN AN CAN PERFORM THESE ACTIONS BASE ON USER_TYPE
        
            if ($_SESSION['USERDATA']['user_type'] == 'admin'){
                //admin actions here 
            }else if ($_SESSION['USERDATA']['user_type'] == 'company'){
                // ACTIONS AVAILABLE FOR A COMPANY PROFILE

                if ($_POST['action'] == 'addProduct'){
                    //adding product details
                    $result['message'] = '
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong><i class="fa fa-check fa-lg"></i> Success!</strong> Product has been added!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                    ';
                    $result['productId'] = $process->setProductDetails($_POST);
                    
                    if ($result['productId'] != false && !empty($_FILES)){
                        //Inserting product pictures
                        $_FILES['productId'] = $result['productId'];

                        $result['productImgSet'] = $process->setProductImages($_FILES);

                        if ($result['productImgSet'] == false){
                            $result['message'] = '
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>We\'re Sorry,</strong> Product images were not saved.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                            ';
                            
                        }
                    
                    }
                    
                    $result['products'] = $process->getCompanyProducts($_SESSION['COMPANYDATA'][0]['id']);
                    
                    // echo "<br><br><br><br>";
                    // echo "<pre>";
                    // print_r($_FILES);

                    $result['sectors'] = $process->getSectors();
                    $pageContent = $view->addProduct($result);

                }else if($_POST['action'] == 'saveProductDetails'){
                    
                    $result['initialPrev'] = array();
                    $result['initialPrevConfig'] = array();

                    $result['result'] = $process->setProductDetails($_POST);

                    if ($result == false){
                        $result['message'] = '
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>We\'re Sorry,</strong> Product Details were not saved.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        ';
                    }else{
                        $result['message'] = '
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong><i class="fa fa-check fa-lg"></i> Success!</strong> Product was updated!
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        ';
                    }
                    
                    $result['sectors'] = $process->getSectors();
                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['COMPANYDATA'][0]['id']);
                    $result['product'] = $process->getCompanyProductDetail($result['companyDetails'][0]['id'], $_POST['productId']);

                    foreach($result['product'][0]['productImages'] as $key => $productImg){

                        $result['initialPrev'] = array(
                            BASE_URL.$productImg['path']
                        );
                        $result['initialPrevConfig'][$key] = array(
                            'caption' => $productImg['file_name'],
                            'url' => BASE_URL.'index.php/?page=removeProductImg',
                            'key' => $productImg['id'],
                            'size' => $productImg['size'],
                            'type' => $productImg['type']

                        );

                    }
                    $pageContent = $view->editMyProducts($result);



                }else{
                    //action not found
                    $pageContent = $view->pageNotFound();
                }
            }
        }else{
            $pageContent = $view->home();
        }
    }else{
        
        $result['sectors'] = $process->getSectors();
        $result['buyerCount'] = count($process->getBuyerList());
        $result['companys'] = $process->getCompanyList();

        $products = $process->getProducts();

        foreach ($products as $key => $product){


            $prodDetails = $process->getCompanyProductDetail($product['company_id'], $product['product_id']);
            $prodDetails[0]['is_featured'] = 0;
           
            if ($product['is_featured'] == 1){
                $prodDetails[0]['is_featured'] = 1;
            }
            
            $result['products'][] = $prodDetails[0];

        }

        $pageContent = $view->home($result);
    }

    // ALL AJAX POST REQUEST ARE HANDLED BELOW

    if(!empty($_POST) && isset($_POST['ajaxRequest'])){
        // AJAX REQUESTS 
    
        if (!empty($_SESSION) && isset($_SESSION['USERDATA']['user_id'])){
            //HANDLE SESSION FOR LOGGED IN USER

            if ($_SESSION['USERDATA']['user_type'] == 'admin'){
                // ALL AJAX REQUESTS AVAILABLE FOR ADMIN ACCOUNT 

                if($_POST['ajaxRequest'] == 'saveMyProfile'){
                    if($process->updateUserProfile($_POST)){
                        $message = 'Changes have been saved!';
                    }else{
                        $message = 'Sorry, changes were not saved, try again later.';
                    }
                    echo $message;
                }

            }else if($_SESSION['USERDATA']['user_type'] == 'company'){
                // ALL AJAX REQUESTS AVAILABLE FOR COMPANY ACCOUNT 

                if($_POST['ajaxRequest'] == 'saveMyProfile'){
                    if($process->updateUserProfile($_POST)){
                        $message = 'Changes have been saved!';
                    }else{
                        $message = 'Sorry, changes were not saved, try again later.';
                    }
                    echo $message;
                }else if($_POST['ajaxRequest'] == 'saveCompanyProfile'){
    
                    $_POST['companyDetail']['logoImagePath'] = $_POST['logoPath'];
                    $message = '';
                    
                    if (isset($_FILES) && $_FILES['businessLogo']['name'] != '' && getimagesize($_FILES['businessLogo']['tmp_name'])){
                        
                        //uploading image if it exist 
                        $target_dir = "./uploads/companyLogos/";
                        $target_file = $target_dir . basename($_FILES["businessLogo"]["name"]);
                        $uploadOk = 1;
                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                        
                        // Check if image file is a actual image or fake image
                        // $check = getimagesize($_FILES["businessLogo"]["tmp_name"]);
        
                        // Check if file already exists
                        if (file_exists($target_file)) {
                            $message .= " Sorry, file already exists.";
                            $uploadOk = 0;
                        }
        
                        // Check file size
                        if ($_FILES["businessLogo"]["size"] > 1000000) {
                            $message .= " Sorry, your file is too large.";
                            $uploadOk = 0;
                        }
        
                        // Allow certain file formats
                        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                            $message .= " Sorry, only JPG, JPEG and PNG files are allowed.";
                            $uploadOk = 0;
                        }
        
                        // Check if $uploadOk is set to 0 by an error
                        if ($uploadOk == 0) {
                            $message .= " Sorry, your file was not uploaded.";
                        } else {
                            // if everything is ok, try to upload file
                            if (move_uploaded_file($_FILES["businessLogo"]["tmp_name"], $target_file)) {
                                $message = "The file ". basename( $_FILES["businessLogo"]["name"]). " has been uploaded.";
                                $_POST['companyDetail']['logoImagePath'] = $target_file;
                            } else {
                                $message = "Sorry, there was an error uploading your file.";
                            }
                        }
        
                    }
                    $result1 = $process->updateCompanyProfile($_POST['companyDetail']);
                    $result2 = $process->setSocialContactList($_POST['socialContacts']);
                    $result3 = $process->setExportMarketList($_POST['exportMarkets']);
        
                    if(!$result1 || !$result2 || !$result3){
                        $message .= 'Sorry, not all of the settings was saved.';
                    }else{
                        
                        $_SESSION['COMPANYDATA'] = $process->getCompanyDetails($_SESSION['COMPANYDATA'][0]['id']);
                        $message = 'Changes were saved!';
                    }
                    echo $message;

                }else if ($_POST['ajaxRequest'] == 'removeExportMarket'){
    
                    $result = $process->removeExportMarketFromList($_POST);
                    
                    if (!$result){
                        echo 'Sorry, unable to remove the Export Market';
                    }else{
                        echo 'Export Market has been removed';
                    }
                    // echo json_encode($_POST);
                }else if ($_POST['ajaxRequest'] == 'removeProductImg'){
    
                    $result = [];
                    
                    $data = [
                        'productImageId' => $_POST['key'],
                        'productId' => $_POST['productId'],
                        'status' => 0
                    ];
    
                    //updates image sets status to 0 - Deleted
                    $result['key'] = $process->setProductImages($data);
    
                    if ($result['key'] == false) {
                        $result['error'] = 'Oh snap! We could not remove the image now. Please try again later.';
                    }
                    echo json_encode($result);
    
                }else if ($_POST['ajaxRequest'] == 'uploadProductImg'){
                    
                    $result = [];

                    $result['error'] = 'No image uploaded';

                    if (!empty($_FILES)){

                        $_FILES['productId'] = $_POST['productId'];

                        $wasUploaded = $process->setProductImages($_FILES);

                        if ($wasUploaded){

                            echo json_encode([]);

                        }else{
                            
                            $result['error'] = 'Sorry, an error Occured while uploading the image';
                            echo json_encode($result);
                            
                        }

                    }else{

                        echo json_encode($result);
                    }

                }else{
                    // request not found
                    echo 'Request not found';
                }

            }else if($_SESSION['USERDATA']['user_type'] == 'buyer'){
                // ALL AJAX REQUESTS AVAILABLE FOR BUYER ACCOUNT 
                
                if($_POST['ajaxRequest'] == 'saveMyProfile'){
                    
                    if($process->updateUserProfile($_POST)){
                        $message = 'Changes have been saved!';
                        $addedInterest = $process->setInterestList($_POST['interest'], $_SESSION['userId']);
    
                        if($addedInterest == false){
                            $message = 'Sorry, Not all changes were saved.';
                        }
                    }else{
                        $message = 'Sorry, changes were not saved, try again later.';
                    }
                    echo $message;

                }else if($_POST['ajaxRequest'] == 'saveBuyerProfile'){
                
                    $result['sectors'] = $process->getSectors();
        
                    // $result['message'] = '
                    //     <div class="alert alert-success alert-dismissible fade show" role="alert">
                    //         <strong>Success!</strong> Account has been created please sign in.
                    //         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    //             <span aria-hidden="true">&times;</span>
                    //         </button>
                    //     </div>
        
                    // ';
                    $result['message'] = 'Success! Profile was updated.';
                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['COMPANYDATA'][0]['id']);
        
                    //return buyer Id upon success
                    $wasUpdated = $process->updateUserProfile($_POST);
                    
                    if ($wasUpdated != false){
        
                        $wasUpdated = $process->setInterestList($_POST['interest'], $_SESSION['USERDATA']['user_id']);
                        
                        if ($wasUpdated == false){
                            
                            $result['message'] = 'Notice: Unable to save selected interest at the moment.';
        
                            // $result['message'] .= '
                            //     <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            //         <strong>Notice:</strong> Unable to save selected interest.
                            //         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            //             <span aria-hidden="true">&times;</span>
                            //         </button>
                            //     </div>
                            // ';
                        }else{
        
                            $_POST['companyId'] = $result['companyDetails'][0]['id'];
                            $wasUpdated = $process->updateCompanyProfile($_POST);
        
                            if ($wasUpdated == false){
                                $result['message'] = 'Notice: Was not able to save company name, try again later.';
                            }
        
                        }
                    }else{ 
                        $result['message'] = 'Notice: Unable to update User Profile';
                        //     <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        //         <strong>Notice:</strong> Unable to create account at the moment.
                        //         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        //             <span aria-hidden="true">&times;</span>
                        //         </button>
                        //     </div>
                        // ';
                    }
                    echo $result['message'];
                    
                }else{
                    echo 'Request was not found';
                }

            }else{
                echo 'Request not found';
            }
        }else{

            //HANDLES AJAX REQUEST FOR GUEST USERS
            if($_POST['ajaxRequest']){

            }else{
                echo 'Request not found';
            }
        }
       
    }else{
        // rendering page
        echo $view->header();
        echo $view->topBar();
        echo $pageContent;
        echo $view->footer();
    }

?>