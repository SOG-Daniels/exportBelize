<?php 
    // The Index.php file is the controller of the application, which request data from the DBMS and returns the appropriate view
    require_once('./class.interface.php');
    require_once('./class.process.php');
    require_once('./class.email.php');

    session_start();

    $view = new Ui();
    $process = new Process();

    $accountOptions = array();

    $pageContent = '';

    if($_GET){
        // HADLES ALL PAGE REQUEST FOR ALL USERS
        
        if ($_GET['page'] == 'signIn'){

            $pageContent = $view->signIn();

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
            
            $result['products'] = $process->getProductsBySector($_GET['sectorId']);
            $result['sectors'] = $process->getSectors();

            $pageContent = $view->viewProducts($result);
        
        }else if ($_GET['page'] == 'productDetail'){    

            $pageContent = $view->productDetails();
        
        }else if ($_GET['page'] == 'companyDetail'){    

            $pageContent = $view->companyDetails();

        }elseif (!empty($_SESSION['USERDATA']) && $_GET){
            // HANDLES ALL PAGE REQUEST FOR SIGNED IN USERS
            
            if (isset($_SESSION['USERDATA']['user_id']) && $_GET['page'] == 'viewProducts'){ 
                
                $result['companyDetails'] = $process->getCompanyDetails($_SESSION['USERDATA']['user_id']);
                $result['socialContactList'] = $process->getSocialContactList($result['companyDetails'][0]['id']);
                $result['exportMarketList'] = $process->getExportMarketList($result['companyDetails'][0]['id']);
                $result['myproducts'] = $process->getCompanyProducts();

                $pageContent = $view->viewProducts();

            }else if ($_SESSION['USERDATA']['user_type'] == 'company' && $_GET['page'] == 'editProduct'){ 
                if(isset($_GET['productId'])){
                    
                    $result['sectors'] = $process->getSectors();
                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['USERDATA']['user_id']);
                    $result['product'] = $process->getCompanyProductDetail($result['companyDetails'][0]['id'], $_GET['productId']);
                    
                    // echo '<br><br><br><br>';
                    // echo '<pre>';
                    // print_r($result);
                    // echo '</pre>';
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
                                'url' => BASE_URL.'index.php/?page=removeProductImg',
                                'key' => $productImg['id']

                            );

                        }
                        $pageContent = $view->editMyProducts($result);
                    }

                }else{
                    $pageContent = $view->home();                    
                }
            }else if ($_SESSION['USERDATA']['user_type'] == 'company' && $_GET['page'] == 'myProducts'){ 
                
                $result['companyDetails'] = $process->getCompanyDetails($_SESSION['USERDATA']['user_id']);
                $result['products'] = $process->getCompanyProducts($result['companyDetails'][0]['id']);
                
                // echo "<br><br><br><br>";
                // print_r($result);
                // echo "<pre>";
                $pageContent = $view->viewMyProducts($result);

            }else if ($_SESSION['USERDATA']['user_type'] == 'company' && $_GET['page'] == 'addProduct'){ 
                
                $result['sectors'] = $process->getSectors();
                
                $pageContent = $view->addProduct($result);

            }else if (isset($_SESSION['USERDATA']['user_type']) && $_GET['page'] == 'profile'){    

                $accountOptions = $process->getUserPages($_SESSION['USERDATA']['user_type']);

                if ($_SESSION['USERDATA']['user_type'] == 'admin'){
                    
                    $pageContent = $view->adminProfile();

                }else if($_SESSION['USERDATA']['user_type'] == 'company'){

                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['USERDATA']['user_id']);
                    $result['socialContacts'] = $process->getSocialContact();
                    $result['socialContactList'] = $process->getSocialContactList($result['companyDetails'][0]['id']);
                    $result['exportMarkets'] = $process->getExportMarkets();
                    $result['exportMarketList'] = $process->getExportMarketList($result['companyDetails'][0]['id']);
                
                    $pageContent = $view->companyProfile($result);

                }else{
                    
                    $result['sectors'] = $process->getSectors();
                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['USERDATA']['user_id']);
                    $result['interest'] = $process->getInterest();

                    $pageContent = $view->buyerProfile($result);
                }

            }else if($_GET['page'] == 'logout'){
                //ENDING USER SESSION

                $_SESSION['USERDATA'] = '';
                $_SESSION['PAGES'] = '';

                unset($_SESSION['USERDATA']);
                unset($_SESSION['PAGES']);
                
                session_destroy();

                $pageContent = $view->home();

            }else{
                //PAGE REQUEST NOT FOUND
                $pageContent = $view->home();
            }
        }else{
            //PAGE REQUEST NOT FOUND
            $pageContent = $view->home();
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
                $_SESSION['COMPANYDATA'] = $process->getCompanyDetails($result['user_id']);
                $_SESSION['PAGES'] = $process->getUserPages($_SESSION['USERDATA']['user_type']); 

                if ($_SESSION['USERDATA']['user_type'] == 'admin'){
                    $pageContent = $view->adminProfile();
                }else if($_SESSION['USERDATA']['user_type'] == 'company'){
                   
                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['USERDATA']['user_id']);
                    $result['socialContacts'] = $process->getSocialContact();
                    $result['socialContactList'] = $process->getSocialContactList($result['companyDetails'][0]['id']);
                    $result['exportMarkets'] = $process->getExportMarkets();
                    $result['exportMarketList'] = $process->getExportMarketList($result['companyDetails'][0]['id']);
                    
                    $pageContent = $view->companyProfile($result);
                }else{
                    
                    $result['sectors'] = $process->getSectors();
                    $result['companyDetails'] = $process->getCompanyDetails($_SESSION['USERDATA']['user_id']);
                    $result['interest'] = $process->getInterest();

                    $pageContent = $view->buyerProfile($result);
                }
                
            }

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
        }else if(!empty($_SESSION['USERDATA']) && $_SESSION['USERDATA']['user_type'] == 'company' && $_POST['action'] == 'addProduct'){

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
            
            // $result['products'] = $process->getCompanyProducts($_SESSION['COMPANYDATA'][0]['id']);
            
            // echo "<br><br><br><br>";
            // echo "<pre>";
            // print_r($_FILES);

            // echo "</pre>";
            // $path_parts = pathinfo($_FILES['file']['name'][0]);
            // echo $path_parts['filename'];
            // echo $path_parts['extension'];

            // $pageContent = $view->viewMyProducts($result);
            $_POST = array();
            $result['sectors'] = $process->getSectors();
            $pageContent = $view->addProduct($result);

            
        }else{
            $pageContent = $view->home();
        }
    }else{
        $pageContent = $view->home();
    }

    if(!empty($_POST) && isset($_POST['ajaxRequest'])){
        // HANDLING POST AJAX REQUEST BELOW
    
        if (!empty($_SESSION) && isset($_SESSION['USERDATA']['user_id'])){
            //HANDLE SESSION FOR LOGGED IN USER

            if($_POST['ajaxRequest'] == 'saveMyProfile'){
                $message = '';
                if ($_SESSION['USERDATA']['user_type'] == 'admin'){
                    $process->updateUserProfile($_POST);
    
                }else if ($_SESSION['USERDATA']['user_type'] == 'company'){
                    if($process->updateUserProfile($_POST)){
                        $message = 'Changes have been saved!';
                    }else{
                        $message = 'Sorry, changes were not saved, try again later.';
                    }
                    echo $message;
    
                }else{
                    //buyer
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
    
                    //update interest
                }
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
                $result['companyDetails'] = $process->getCompanyDetails($_SESSION['USERDATA']['user_id']);
    
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
                    
                    $_SESSION['COMPANYDATA'] = $process->getCompanyDetails($_SESSION['USERDATA']['user_id']);
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
            }else{
                // request not found
                echo 'Request was not found';
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