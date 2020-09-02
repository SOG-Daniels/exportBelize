<?php 
// The UI class manages the different layouts for the application
class Ui {

    private $title = '';
    
    
    function __construct(){
        $this->title = 'Belize Export';
        
    }
    // returns the starting header of html page structure
    public function header(){
        $html = '
                <!DOCTYPE html>
                <html lang="zxx">
                
                <head>
                <meta charset="utf-8">
                <title>'.$this->title.'</title>
                
                <!-- mobile responsive meta -->
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
                
                    
                    <!-- FontAwesome -->
                    <link rel="stylesheet" href="'.BASE_URL.'plugins/fontawesome/font-awesome.min.css">
                    
                    <!-- FontAwesome - free -->
                    <link rel="stylesheet" href="'.BASE_URL.'plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
                    
                    <!-- ** Plugins Needed for the Project ** -->
                    <!-- Bootstrap -->
                    <link rel="stylesheet" href="'.BASE_URL.'plugins/bootstrap/bootstrap.min.css">
                    
                    <!-- datatables -->
                    <link rel="stylesheet" href="'.BASE_URL.'plugins/datatables/dataTables.bootstrap4.min.css">

                    <!-- Animation -->
                    <link rel="stylesheet" href="'.BASE_URL.'plugins/animate.css">

                    <!-- Prettyphoto -->
                    <link rel="stylesheet" href="'.BASE_URL.'plugins/prettyPhoto.css">
                    
                    <!-- Owl Carousel -->
                    <link rel="stylesheet" href="'.BASE_URL.'plugins/owl/owl.carousel.css">
                    <link rel="stylesheet" href="'.BASE_URL.'plugins/owl/owl.theme.css">

                    <!-- Flexslider -->
                    <link rel="stylesheet" href="'.BASE_URL.'plugins/flex-slider/flexslider.css">
                    <!-- Flexslider -->
                    <link rel="stylesheet" href="'.BASE_URL.'plugins/cd-hero/cd-hero.css">

                    <!-- Style Swicther -->
                    <link id="style-switch" href="'.BASE_URL.'css/presets/preset3.css" media="screen" rel="stylesheet" type="text/css">
                    
                    <!-- File Input Master Plugin css file -->
                    <link  href="'.BASE_URL.'plugins/bootstrap-fileinput-master/css/fileinput.min.css" media="screen" rel="stylesheet" type="text/css">
                    
                    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
                    <!--[if lt IE 9]>
                    <script src="'.BASE_URL.'plugins/html5shiv.js"></script>
                    <script src="'.BASE_URL.'plugins/respond.min.js"></script>
                    <![endif]-->
                
                    <!-- Main Stylesheet -->
                    <link href="'.BASE_URL.'css/style.css" rel="stylesheet">
                    <link href="'.BASE_URL.'css/customStyles.css" rel="stylesheet">
                
                    <!--Favicon-->
                    <!--<link rel="icon" href="img/favicon/favicon-32x32.png" type="image/x-icon" />-->
                    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/favicon/favicon-144x144.png">
                    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/favicon/favicon-72x72.png">
                    <link rel="apple-touch-icon-precomposed" href="img/favicon/favicon-54x54.png">
                    
                    
                    <!-- custom javascript set and getfunction for global javascript variables that will be neded-->
                    <script src="'.BASE_URL.'js/globals.js"></script>
                
                </head>
                <body>
                <div class="body-inner">
                
                ';
        return $html;
    }
    //returns the top tool bar
    public function topbar(){

        $moreOptions = '';
        $profileDropDown = '';



        if (!empty($_SESSION) && !empty($_SESSION['PAGES'])){
            //pages were passed
            foreach ($_SESSION['PAGES'] as $page){
               $moreOptions .= '
                    <a class="dropdown-item" href="'.BASE_URL.'index.php'.$page['link'].'">'.$page['display_name'].'</a>
               '; 
            }
            $profileDropDown = '
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        Profile
                    </a>
                    <div class="dropdown-menu">
                    '.$moreOptions.'
                        <a class="dropdown-item" href="'.BASE_URL.'index.php/?page=logout">Logout</a>
                    </div>
                </li>
            ';
        }else{
            $profileDropDown = '
                <li class="nav-item pl-md-4 pt-md-2">
                    <a href="'.BASE_URL.'?page=signIn" class="nav-item btn btn-primary">Sign In</a>
                </li>
            ';
        }

        $html = '
            <!-- Header start -->
            <header id="header" class="fixed-top header4" role="banner">
                <div class="container">
                    <nav class="navbar navbar-expand-lg navbar-light px-4 py-2">
                        <a class="navbar-brand" href="'.BASE_URL.'"><img class="img-fluid" src="'.BASE_URL.'images/export-belize-logo-2.png" alt="logo"></a>
                        <button class="navbar-toggler ml-auto border-0 rounded-0 text-dark" type="button" data-toggle="collapse"
                            data-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fa fa-bars"></span>
                        </button>
            
                        <div class="collapse navbar-collapse text-center" id="navigation">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="'.BASE_URL.'">Home</a></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="'.BASE_URL.'index.php/?page=viewProducts">View Products</a></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="'.BASE_URL.'index.php/?page=aboutUs">About Us</a></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="'.BASE_URL.'index.php/?page=contact">Contact</a></a>
                                </li>
                                '.$profileDropDown.'
                                
                            </ul>
                        </div>
                    </nav>
                </div>
            </header>
            <!--/ Header end -->

        ';
        return $html;
    }   
    // return the home page
    public function home(){
        $html = '
            <!-- Slider start -->
            <section id="home" class="pt-md-0 pt-3*2 pb-0">
                <div id="main-slide" class="carousel slide" data-ride="carousel">
                    <div class="overlay"></div>
                    <ol class="carousel-indicators">
                        <li data-target="#main-slide" data-slide-to="0" class="active"></li>
                        <li data-target="#main-slide" data-slide-to="1"></li>
                        <li data-target="#main-slide" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="img-fluid" src="'.BASE_URL.'images/slider2/bg1.jpg" alt="slider">
                            <div class="slider-content">
                                <div class="col-md-12 text-center">
                                    <h2 class="animated2">
                                        Need To Invent The Future!
                                    </h2>
                                    <h3 class="animated3">
                                        We Making Difference To Great Things Possible
                                    </h3>
                                    <p class="animated4"><a href="#" class="slider btn btn-primary white">Check Now</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="img-fluid" src="'.BASE_URL.'images/slider2/bg2.jpg" alt="slider">
                            <div class="slider-content">
                                <div class="col-md-12 text-center">
                                    <h2 class="animated4">
                                        How Big Can You Dream?
                                    </h2>
                                    <h3 class="animated5">
                                        We are here to make it happen
                                    </h3>
                                    <p class="animated6"><a href="#" class="slider btn btn-primary white">Buy Now</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="img-fluid" src="'.BASE_URL.'images/slider2/bg3.jpg" alt="slider">
                            <div class="slider-content">
                                <div class="col-md-12 text-center">
                                    <h2 class="animated7">
                                        Your Challenge is Our Progress
                                    </h2>
                                    <h3 class="animated8">
                                        So, You Don\'t Need to Go Anywhere Today
                                    </h3>
                                    <div>
                                        <a class="animated4 slider btn btn-primary btn-min-block white" href="#">Get Now</a>
                                        <a class="animated4 slider btn btn-primary btn-min-block solid" href="#">Live Demo</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="left carousel-control carousel-control-prev" href="#main-slide" data-slide="prev">
                        <span><i class="fa fa-angle-left"></i></span>
                    </a>
                    <a class="right carousel-control carousel-control-next" href="#main-slide" data-slide="next">
                        <span><i class="fa fa-angle-right"></i></span>
                    </a>
                </div>
            </section>

            <!--/ Slider end -->
            <section class="call-to-action dark">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h3>Get started by registering as a belizean business or buyer</h3>
                            <a href="'.BASE_URL.'index.php?page=buyerRegistration" class="float-right btn btn-primary solid">Buyer</a>
                            <a href="'.BASE_URL.'index.php?page=companyRegistration" class="float-right btn btn-primary white">Belizean Business</a>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Portfolio start / is now featured Products -->
            <section id="portfolio" class="portfolio">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 heading">
                            <span class="title-icon classic float-left"><i class="fa fa-shopping-cart"></i></span>
                            <h2 class="title">Featured Products<span class="title-desc">Check out these new products!</span></h2>
                        </div>
                    </div> <!-- Title row end -->

                </div>

                <div class="container-fluid">
                    <div class="row isotope" id="isotope">
                        <div class="col-sm-4 isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="'.BASE_URL.'images/products/Marie-Sharps-Habanero-Pepper-Sauce-Fiery-Hot.jpg" alt="">
                                    <figcaption>
                                        <h3>Product 1</h3>
                                        <a class="link icon-pentagon" href="'.BASE_URL.'?page=productDetail&productId=1"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->

                        <div class="col-sm-4 isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="'.BASE_URL.'images/products/Marie-Sharps-Habanero-Pepper-Sauce-Fiery-Hot.jpg" alt="">
                                    <figcaption>
                                        <h3>Product 2</h3>
                                        <a class="link icon-pentagon" href="'.BASE_URL.'?page=productDetail&productId=2"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->

                        <div class="col-sm-4 isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="'.BASE_URL.'images/products/Marie-Sharps-Habanero-Pepper-Sauce-Fiery-Hot.jpg" alt="">
                                    <figcaption>
                                        <h3>Product 3</h3>
                                        <a class="link icon-pentagon" href="'.BASE_URL.'?page=productDetail&productId=3"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->

                    </div><!-- Content row end -->
                </div><!-- Container end -->
            </section><!-- Portfolio end -->
        
            <!-- Counter Strat / displays some basic stat info-->
            <section class="ts_counter p-0">
                <div class="container-fluid">
                    <div class="row facts-wrapper wow fadeInLeft text-center">
            
                        <div class="facts one col-md-3 col-sm-6">
                            <span class="facts-icon"><i class="fa fa-shopping-cart"></i></span>
                            <div class="facts-num">
                                <span class="counter">700</span>
                            </div>
                            <h3>Products</h3>
                        </div>
                        <div class="facts two col-md-3 col-sm-6">
                            <span class="facts-icon"><i class="fa fa-th-list"></i></span>
                            <div class="facts-num">
                                <span class="counter">7</span>
                            </div>
                            <h3>Different Categories</h3>
                        </div>
                        <div class="facts three col-md-3 col-sm-6">
                            <span class="facts-icon"><i class="fa fa-building"></i></span>
                            <div class="facts-num">
                                <span class="counter">27</span>
                            </div>
                            <h3>Registered Companies</h3>
                        </div>
            
                        <div class="facts four col-md-3 col-sm-6">
                            <span class="facts-icon"><i class="fa fa-user"></i></span>
                            <div class="facts-num">
                                <span class="counter">100</span>
                            </div>
                            <h3>Registered Buyers</h3>
                        </div>

            
            
                    </div>
                </div>
                <!--/ Container end -->
            </section>
            <!--/ Counter end -->
        
            <!-- Featured Categories -->
            <section id="portfolio" class="portfolio">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 heading">
                            <span class="title-icon classic float-left"><i class="fa fa-th-list"></i></span>
                            <h2 class="title">Featured Categories<span class="title-desc">Check out these categories!</span></h2>
                        </div>
                    </div> <!-- Title row end -->

                </div>

                <div class="container-fluid">
                    <div class="row isotope" id="isotope">
                        <div class="col-sm-6 isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="'.BASE_URL.'images/sectors/agriculture.jpg" alt="">
                                    <figcaption>
                                        <h3>AGRICULTURE</h3>
                                        <a class="link icon-pentagon" href="'.BASE_URL.'?page=viewProducts&sector=agriculture"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->

                        <div class="col-sm-6 isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="'.BASE_URL.'images/sectors/agro-processing.jpg" alt="">
                                    <figcaption>
                                        <h3>AGRO-PROCESSING</h3>
                                        <a class="link icon-pentagon" href="'.BASE_URL.'?page=viewProducts&sector=agro-processing"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->
                    
                        <div class="col-sm-4 isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="'.BASE_URL.'images/sectors/fishes-and-marine.jpg" alt="">
                                    <figcaption>
                                        <h3>Fishes and Marine</h3>
                                        <a class="link icon-pentagon" href="'.BASE_URL.'?page=viewProducts&sector=fishes-and-marine"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->

                        <div class="col-sm-4 isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="'.BASE_URL.'images/sectors/energy.jpg" alt="">
                                    <figcaption>
                                        <h3>Energy</h3>
                                        <a class="link icon-pentagon" href="'.BASE_URL.'?page=viewProducts&sector=energy"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->
                        <div class="col-sm-4 isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="'.BASE_URL.'images/sectors/light-manufacturing.jpg" alt="">
                                    <figcaption>
                                        <h3>Light Manufacturing</h3>
                                        <a class="link icon-pentagon" href="'.BASE_URL.'?page=viewProducts&sector=light-manufacturing"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->
                    </div><!-- Content row end -->
                </div><!-- Container end -->
            </section><!-- Portfolio end -->

            <!-- Featured companies -->
            <section id="team" class="team">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 heading">
                            <span class="title-icon float-left"><i class="fa fa-building"></i></span>
                            <h2 class="title">Featured Companies<span class="title-desc">Our clients are ready to provide the products you\'re looking for.</span></h2>
                        </div>
                    </div><!-- Title row end -->
                    <div class="row wow fadeInLeft">
                        <div id="client-carousel" class="col-sm-12 owl-carousel owl-theme text-center client-carousel">
                            <figure class="m-0 item client_logo">
                                <a href="'.BASE_URL.'?page=companyDetail">
                                    <img src="'.BASE_URL.'images/clients/marie-sharps-logo.png" alt="client">
                                </a>
                            </figure>
                            <figure class="m-0 item client_logo">
                                <a href="'.BASE_URL.'?page=companyDetail">
                                    <img src="'.BASE_URL.'images/clients/marie-sharps-logo.png" alt="client">
                                </a>
                            </figure>
                            <figure class="m-0 item client_logo">
                                <a href="'.BASE_URL.'?page=companyDetail">
                                    <img src="'.BASE_URL.'images/clients/marie-sharps-logo.png" alt="client">
                                </a>
                            </figure>
                            <figure class="m-0 item client_logo">
                                <a href="'.BASE_URL.'?page=companyDetail">
                                    <img src="'.BASE_URL.'images/clients/marie-sharps-logo.png" alt="client">
                                </a>
                            </figure>
                            <figure class="m-0 item client_logo">
                                <a href="'.BASE_URL.'?page=companyDetail">
                                    <img src="'.BASE_URL.'images/clients/marie-sharps-logo.png" alt="client">
                                </a>
                            </figure>
                            <figure class="m-0 item client_logo">
                                <a href="'.BASE_URL.'?page=companyDetail">
                                    <img src="'.BASE_URL.'images/clients/marie-sharps-logo.png" alt="client">
                                </a>
                            </figure>
                            <figure class="m-0 item client_logo">
                                <a href="'.BASE_URL.'?page=companyDetail">
                                    <img src="'.BASE_URL.'images/clients/marie-sharps-logo.png" alt="client">
                                </a>
                            </figure>
                            <figure class="m-0 item client_logo">
                                <a href="'.BASE_URL.'?page=companyDetail">
                                    <img src="'.BASE_URL.'images/clients/marie-sharps-logo.png" alt="client">
                                </a>
                            </figure>
                        </div><!-- Owl carousel end -->
                    </div><!-- Main row end -->
                </div>
            </section>
        ';
        return $html;
    }
    // return html struture for a banner
    public function banner($title = null, $breadCrumbs = null){
        $html = '
        <div id="banner-area" class="pt-4">
            <img src="'.BASE_URL.'images/banner/banner1.jpg" alt="" />
            <div class="parallax-overlay"></div>
            <!-- Subpage title start -->
            <div class="banner-title-content">
                <div class="text-center">
                    <h2>'.($title ?? '').'</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="'.BASE_URL.'">Home</a></li>
                            '.($breadCrumbs ?? '').'
                        </ol>
                    </nav>
                </div>
            </div><!-- Subpage title end -->
        </div><!-- Banner area end -->
        ';
        return $html;
    }
    //returns a search bar
    public function searchBar($data = null){
        $sectorOptions = '';

        foreach ($data as $sector){
            $sectorOptions .= '
                <option value="'.$sector['id'].'">'.$sector['name'].'</option>
            ';
        }
        $html = '
            <div class="container pt-3">
                <div class="row">
                    <div class="col-md-12 mx-auto">
                        <div class="card shadow" >
                            <div class="card-body">
                                <h3 class="card-title">Search For ... </h3>
                                <div class="row">
                                    <div class="col-md-4 col-12 ">
                                        <label for="inputEmail3"><h4 class="d-inline">Sector</h4></label>
                                        <div class="input-group">
                                            <select class="form-control">
                                                <option value="0"> NONE </option>
                                                '.$sectorOptions.' 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12 ">
                                        <label for="search"><h4 class="d-inline">HS Code</h4></label>
                                                <input type="text" name="hsCode" class="form-control" placeholder="eg. H320" aria-label="" aria-describedby="basic-addon2">
                                    </div>  
                                    <div class="col-md-4 col-12 ">
                                        <label for="search"><h4 class="d-inline">Product Name</h4></label>
                                        <div class="input-group">
                                                <input type="text" class="form-control" placeholder="eg. Habanero Sauce..." aria-label="" aria-describedby="basic-addon2">
                                                <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button"><i class="fa fa-search fa-lg"></i></button>
                                                </div>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ';
        return $html;
    }
    public function viewProducts($data = null){

        $html = $this->banner('Products', 
            '<li class="breadcrumb-item text-white" aria-current="page">Products</li>'
            ).$this->searchBar($data['sectors'] ?? '').'

                    <!-- Portfolio start -->
                    <section id="main-container" class="portfolio-static">
                        <div class="container">
                            
                            <div class="row">
                                <div class="col-md-12 heading">
                                    <span class="title-icon classic float-left"><i class="fa fa-shopping-cart"></i></span>
                                    <h2 class="title classic">Products Found</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 pb-2">
                                    <div class="grid">
                                        <figure class="m-0 effect-oscar">
                                            <img src="images/portfolio/portfolio1.jpg" alt="">
                                            <figcaption>
                                                <h3>Product Name</h3>
                                                <a class="link icon-pentagon" href="'.BASE_URL.'index.php?page=productDetail"><i class="fa fa-link"></i></a>
                                            </figcaption>
                                        </figure>
                                        <div class="portfolio-static-desc">
                                        <h3>Product Name  <a class="link" href="#"><!--<i class="fa fa-heart-o fa-lg"></i>--></a></h3>
                                        </div>
                                    </div>
                                    <!--/ grid end -->
                                </div>
                                <div class="col-sm-3 pb-2">
                                    <div class="grid">
                                        <figure class="m-0 effect-oscar">
                                            <img src="images/portfolio/portfolio2.jpg" alt="">
                                            <figcaption>
                                                <h3>Product Name</h3>
                                                <a class="link icon-pentagon" href="'.BASE_URL.'?page=viewProducts&sector=energy"><i class="fa fa-link"></i></a>
                                            </figcaption>
                                        </figure>
                                        <div class="portfolio-static-desc">
                                        <h3>Product Name  <a class="link" href="#"><!--<i class="fa fa-heart-o fa-lg"></i>--></a></h3>
                                        </div>
                                    </div>
                                </div>
                                
                             
                            </div><!-- Content row end -->
                        </div><!-- Container end -->
                    </section><!-- Portfolio end -->

        ';
        return $html;

    }
    public function viewMyProducts($data = null){

        $rowData = '';
        $count = 1;

        foreach ($data['products'] as $product){
            
            $rowData .= '
                <tr>
                    <td>'.$count.'</td>
                    <td>'.$product['hs_code'].'</td>
                    <td>'.$product['product_name'].'</td>
                    <td>'.$product['sector_name'].'</td>
                    <td><a class="btn btn-link pt-0" href="'.BASE_URL.'index.php/?page=editProduct&productId='.$product['product_id'].'" ><i class="fa fa-edit"></i> Edit</td>
                    <td><a class="btn btn-link text-danger remove-product pt-0" href="'.BASE_URL.'index.php/?page=removeProduct&productId='.$product['product_id'].'" ><i class="fa fa-trash"></i> Delete</td>
                </tr>
            ';
            $count++;
        }

        $html = $this->banner('My Products', 
                              '<li class="breadcrumb-item text-white" aria-current="page">View my Products</li>'
                              ).'

                    <!-- Portfolio start -->
                    <section id="main-container" class="portfolio-static pt-4">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 heading">
                                    <span class="title-icon classic float-left"><i class="fa fa-shopping-cart"></i></span>
                                    <h2 class="title classic">My Products</h2>
                                </div>
                                <div class="col-12">
                                    '.($data['message'] ?? '').'
                                </div>
                            </div>
                            <div class="card shadow">
                                <div class="card-header bg-light-grey pb-2">
                                    <h4 class="text-dark d-inline">
                                    PRODUCT LIST
                                    </h4>
                                    <span class="float-right">
                                        <a class="btn bs-btn-primary btn-sm" href="'.BASE_URL.'index.php/?page=addProduct"><i class="fa fa-plus"></i> Add Product</a>
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-strip" width="100%" class="display" id="dataTable" cellspacing="0">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>HS Code</th>
                                                    <th>Product Name</th>
                                                    <th>Sector</th>
                                                    <th>Action</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                '.($rowData ?? '').'
                                            </tbody>
                                            <tfoot class="bg-light-grey">
                                                <tr>
                                                    <th>#</th>
                                                    <th>HS Code</th>
                                                    <th>Product Name</th>
                                                    <th>Sector</th>
                                                    <th>Action</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Container end -->
                    </section><!-- Portfolio end -->

        ';
        return $html;

    }
    public function editMyProducts($data = null){
        
        $sectorOptions = '';

        foreach ($data['sectors'] as $sector){
            $isSector = '';
            if ($data['product'][0]['sector_id'] == $sector['id']){
                $isSector = 'selected';
            }
            $sectorOptions .= '
                <option value="'.$sector['id'].'" '.$isSector.' >'.$sector['name'].'</option>
            ';
        }

        $html = $this->banner('Edit Product',
                              '<li class="breadcrumb-item"><a href="'.BASE_URL.'index.php/?page=myProducts">My Products</a></li>'.
                              '<li class="breadcrumb-item text-white" aria-current="page">Edit Product</li>'
                              ).'

                    <script>    
                        setInitialPreview('.json_encode($data['initialPrev']).');
                        setInitialPreviewConfig('.json_encode($data['initialPrevConfig']).');
                    </script>

                    <!-- Portfolio start -->
                    <section id="main-container" class="portfolio-static pt-4">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 heading">
                                    <span class="title-icon classic float-left"><i class="fa fa-edit"></i></span>
                                    <h2 class="title classic">Edit My Product</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="accordion" id="accordion">
                                        <div class="card border rounded mb-2">
                                            <form id="my-product-1" action="'.BASE_URL.'" method="POST">
                                                <div class="card-header p-0 bg-light-grey">
                                                    <a class="h4 mb-0 font-weight-bold text-uppercase d-block p-2 pl-5 text-dark" data-toggle="collapse" data-target="#collapseOne"
                                                        aria-expanded="true" aria-controls="collapseOne">Product Info
                                                    </a>
                                                </div>
                                                <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                                    <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-12 col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="prodName" class="">Product Name</label>
                                                                        <input type="text" class="form-control" name="prodName" id="" placeholder="Enter a product name..." value="'.($data['product'][0]['product_name']).'" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="HScode" class="">Hs Code</label>
                                                                        <input type="text" class="form-control" name="prodName" id="" placeholder="Enter the product\'s Hs Code..." value="'.($data['product'][0]['hs_code']).'" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="">Sector</label>
                                                                        <select name="sector[][sectorId]" class="form-control">
                                                                            '.$sectorOptions.'
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="prodName" class="">Product Description</label>
                                                                        <textarea class="form-control" name="productDescription" placeholder="Enter something about the product..." rows="3">'.($data['product'][0]['product_description']).'</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <span class="float-right"><button class="btn btn-success"><i class="fa fa-save"></i> Save</button> </span>
                                                                </div>
                                                            </div>
                                                        </form> 
                                                
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!--/ Panel 1 end-->

                                        <div class="card border rounded mb-2">
                                            <div class="card-header p-0 bg-light-grey">
                                                <a class="h4 collapsed mb-0 font-weight-bold text-uppercase d-block p-2 pl-5 text-dark" data-toggle="collapse" data-target="#collapseTwo"
                                                    aria-expanded="true" aria-controls="collapseTwo">
                                                    Product Images
                                                </a>
                                            </div>
                                            <div id="collapseTwo" class="collapse" data-parent="#accordion">
                                                <div class="card-body">
                                                    <form id="my-product-1" action="'.BASE_URL.'" method="POST">
                                                        <div class="row">
                                                            <div class="col-12 col-md-12">
                                                                <div class="file-loading">
                                                                    <input id="input-fa" name="input-fa[]" type="file" data-classButton="btn btn-secondary" accept=".jpg,.png,.jpeg" multiple>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </form> 
                                                </div>
                                            </div>
                                        </div>
                                        <!--/ Panel 2 end-->

                                    </div>
                                </div>
                            </div>
                        </div><!-- Container end -->
                    </section><!-- Portfolio end -->

        ';
        return $html;

    }
    public function addProduct($data = null){
        
        $sectorOptions = '';

        foreach ($data['sectors'] as $sector){
            $sectorOptions .= '
                <option value="'.$sector['id'].'">'.$sector['name'].'</option>
            ';
        }

        $html = $this->banner('Add Product',
                              '<li class="breadcrumb-item"><a href="'.BASE_URL.'index.php/?page=myProducts">My Products</a></li>'.
                              '<li class="breadcrumb-item text-white" aria-current="page">Add Product Form</li>'
                              ).'

                    <!-- Portfolio start -->
                    <section id="main-container" class="portfolio-static pt-4">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 heading">
                                    <span class="title-icon classic float-left"><i class="fa fa-shopping-cart"></i></span>
                                    <h2 class="title classic">Add Product</h2>
                                </div>
                                <div class="col-12">
                                    '.($data['message'] ?? '').'
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="accordion" id="accordion">
                                        <div class="card border rounded mb-2">
                                            <form action="'.BASE_URL.'" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="action" value="addProduct">
                                                <div class="card-header p-0 bg-light-grey">
                                                    <a class="h4 mb-0 font-weight-bold text-uppercase d-block p-2 pl-5 text-dark" data-toggle="collapse" data-target="#collapseOne"
                                                        aria-expanded="true" aria-controls="collapseOne">Product Info
                                                    </a>
                                                </div>
                                                <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                                    <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-12 col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="prodName" class="">Product Name</label>
                                                                        <input type="text" class="form-control" name="prodName" id="" placeholder="Enter a product name..." value="" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="prodName" class="">HS Code</label>
                                                                        <input type="text" class="form-control" name="hs_code" id="" placeholder="Enter the product\'s HS Code..." value="" >
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="">Sector</label>
                                                                        <select name="sectorId" class="form-control">
                                                                            '.$sectorOptions.'
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="prodName" class="">Product Description</label>
                                                                        <textarea class="form-control" name="productDescription" placeholder="Enter something about the product..." rows="3"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12 col-md-12">
                                                                    <div class="file-loading">
                                                                        <input id="add-product-images" name="file[]" type="file" data-classButton="btn btn-secondary" accept=".jpg,.png,.jpeg" multiple>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row pt-4">
                                                                <div class="col-12">
                                                                    <span class="float-right"><button class="btn btn-success"><i class="fa fa-check"></i> Add Product</button> </span>
                                                                </div>
                                                            </div>
                                                        </form> 
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!--/ Panel 1 end-->
                                    </div>
                                </div>
                            </div>
                        </div><!-- Container end -->
                    </section><!-- Portfolio end -->

        ';
        return $html;

    }
    public function productDetails(){
        $html = '
            '.$this->banner('Product Name', 
            '<li class="breadcrumb-item text-white" aria-current="page">Product Name</li>'
            ).'   

            <!-- Portfolio item start -->
            <section id="portfolio-item">
                <div class="container">
                    <!-- Portfolio item row start -->
                    <div class="row">
                        <!-- Portfolio item slider start -->
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <div class="portfolio-slider">
                                <div class="flexportfolio flexslider">
                                    <ul class="slides">
                                        <li><img src="images/products/Marie-Sharps-Habanero-Pepper-Sauce-Fiery-Hot.jpg" alt=""></li>
                                        <li><img src="images/products/marie-sharp-habanero-sauce.jpg" alt=""></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Portfolio item slider end -->
                        <!-- sidebar start -->
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pt-3 pt-md-0">
                            <div class="sidebar">
                                <div class="portfolio-desc">
                                    <h3 class="widget-title">About Product</h3>
                                    <p>Oat cake oat cake dessert brownie. Gummies tiramisu tart jujubes jelly beans macaroon danish pie muffin.
                                        Soufflé pudding brownie pudding fruitcake marshmallow.
                                    </p>
                                    <br />
                                    <h3 class="widget-title">Additional Info</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.</p>
                                    <br />
                                    <h3 class="widget-title">Exports To</h3>
                                    <p>Us, Taiwan, Japan, Korea</p>
                                </div>
                            </div>
                        </div>
                        <!-- sidebar end -->
                    </div><!-- Portfolio item row end -->
                </div><!-- Container end -->
            </section><!-- Portfolio item end -->
            
            <div class="row about-wrapper-bottom">
                <div class="col-md-6 ts-padding about-img"
                    style="height:374px;">
                                    <img src="images/clients/marie-sharps-logo.png" alt="client">
                </div>
                <!--/ About image end -->
                <div class="col-md-6 ts-padding about-message">
                <h3 class="widget-title"><i class="fa fa-building"></i> Company</h3>
                <h3>MARIE SHARP\'s</h3>
                    <p>Cras mattis consectetur purus sit amet fermentum. Etiam porta sem malesuada magna mollis euismod. Aenean eu
                        leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Sed posuere consectetur est at lobortis.
                    </p>
                    <ul class="unstyled arrow">
                        <li><a href="#"><i class="fa fa-globe info"></i> http:/www.somebusinesslink.com</a></li>
                        <li><a href="'.BASE_URL.'?page=companyDetail#company-products"><i class="fa fa-shopping-cart info"></i> See Products</a></li>
                        <li><a href="#main-container"><i class="fa fa-envelope info"></i> Contact</a></li>
                    
                    </ul>
                </div>
                <!--/ About message end -->
            </div>
            <section id="main-container" >
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="card shadow" >
                                <div class="card-body">
                                    <h3 class="card-title">Connect With Us!</h3>

                                    <form id="contact-form" action="contact-form.php" method="post" role="form">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input class="form-control" name="name" id="name" placeholder="" type="text" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input class="form-control" name="email" id="email" placeholder="" type="email" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Subject</label>
                                                    <input class="form-control" name="subject" id="subject" placeholder="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Message</label>
                                            <textarea class="form-control" name="message" id="message" placeholder="" rows="10" required></textarea>
                                        </div>
                                        <div class="text-right"><br>
                                            <button class="btn btn-primary solid blank" type="submit">Send Message</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </section>

        ';
        return $html;
    }
    public function companyDetails($data = array()){
        $html = '
            
            '.$this->banner($data['companyDetails'][0]['name'], 
            '<li class="breadcrumb-item text-white" aria-current="page">'.$data['companyDetails'][0]['name'].'</li>'
            ).'   
        
            <div class="row about-wrapper-bottom">
                <div class="col-md-6 ts-padding about-img">
                                    <img src="images/clients/marie-sharps-logo.png" alt="logo">
                </div>
                <!--/ About image end -->
                <div class="col-md-6 ts-padding about-message">
                <h3 class="widget-title"><i class="fa fa-building"></i> Company</h3>
                <h3>Marie Sharp\'s</h3>
                    <p>Cras mattis consectetur purus sit amet fermentum. Etiam porta sem malesuada magna mollis euismod. Aenean eu
                        leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Sed posuere consectetur est at lobortis.
                    </p>
                    <ul class="unstyled arrow">
                        <li><a href="#"><i class="fa fa-globe info"></i> http:/www.somebusinesslink.com</a></li>
                        <li><a href="#company-products"><i class="fa fa-shopping-cart "></i> See All Products</a></li>
                        <li><a href="#main-container-2"><i class="fa fa-envelope "></i> Contact</a></li>
                    
                    </ul>
                </div>
                <!--/ About message end -->
            </div>
            <!-- Company Products start -->
            <section id="company-products" class="portfolio portfolio-box">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 heading text-center">
                            <span class="icon-pentagon wow bounceIn"><i class="fa fa-shopping-cart"></i></span>
                            <h2 class="title2">Company Products
                                <span class="title-desc">Check out the different products made by Company Name</span>
                            </h2>
                        </div>
                    </div> <!-- Title row end -->

                    <!--Isotope filter start -->
                    <div class="row text-center" >
                        <div class="col-12">
                            <div class="isotope-nav" data-isotope-nav="isotope">
                                <ul>
                                    <li><a href="#" class="active" data-filter="*">All</a></li>
                                    <li><a href="#" data-filter=".web-design">Sub Category</a></li>
                                    <li><a href="#" data-filter=".development">Sub Category</a></li>
                                    <li><a href="#" data-filter=".joomla">Sub Category</a></li>
                                    <li><a href="#" data-filter=".wordpress">Sub Category</a></li>
                                </ul>
                            </div>
                        </div>
                    </div><!-- Isotope filter end -->

                    <div id="isotope" class="row isotope">
                        <div class="col-sm-3 web-design isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="images/portfolio/portfolio1.jpg" alt="">
                                    <figcaption>
                                        <h3>Startup Business</h3>
                                        <a class="link icon-pentagon" href="portfolio-item.html"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->

                        <div class="col-sm-3 development isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="images/portfolio/portfolio2.jpg" alt="">
                                    <figcaption>
                                        <h3>Easy to Lanunch</h3>
                                        <a class="link icon-pentagon" href="portfolio-item.html"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->

                        <div class="col-sm-3 joomla isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="images/portfolio/portfolio3.jpg" alt="">
                                    <figcaption>
                                        <h3>Your Business</h3>
                                        <a class="link icon-pentagon" href="portfolio-item.html"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->

                        <div class="col-sm-3 wordpress isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="images/portfolio/portfolio4.jpg" alt="">
                                    <figcaption>
                                        <h3>Prego Match</h3>
                                        <a class="link icon-pentagon" href="portfolio-item.html"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->

                        <div class="col-sm-3 joomla isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="images/portfolio/portfolio5.jpg" alt="">
                                    <figcaption>
                                        <h3>Fashion Brand</h3>
                                        <a class="link icon-pentagon" href="portfolio-item.html"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->

                        <div class="col-sm-3 development isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="images/portfolio/portfolio6.jpg" alt="">
                                    <figcaption>
                                        <h3>The Insidage</h3>
                                        <a class="link icon-pentagon" href="portfolio-item.html"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->

                        <div class="col-sm-3 development isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="images/portfolio/portfolio7.jpg" alt="">
                                    <figcaption>
                                        <h3>Light Carpet</h3>
                                        <a class="link icon-pentagon" href="portfolio-item.html"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->

                        <div class="col-sm-3 development isotope-item">
                            <div class="grid">
                                <figure class="m-0 effect-oscar">
                                    <img src="images/portfolio/portfolio8.jpg" alt="">
                                    <figcaption>
                                        <h3>Amazing Keyboard</h3>
                                        <a class="link icon-pentagon" href="portfolio-item.html"><i class="fa fa-link"></i></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- Isotope item end -->
                    </div><!-- Content row end -->
                </div><!-- Container end -->
            </section><!-- Portfolio end -->

            <section id="main-container-2">
                <div class="container" ">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card shadow" >
                                <div class="card-body">
                                    <h3 class="card-title">Connect With Us!</h3>
                                    <form id="contact-form" action="contact-form.php" method="post" role="form">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input class="form-control" name="name" id="name" placeholder="" type="text" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input class="form-control" name="email" id="email" placeholder="" type="email" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Subject</label>
                                                    <input class="form-control" name="subject" id="subject" placeholder="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Message</label>
                                            <textarea class="form-control" name="message" id="message" placeholder="" rows="10" required></textarea>
                                        </div>
                                        <div class="text-right"><br>
                                            <button class="btn btn-primary solid blank" type="submit">Send Message</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="contact-info">
                                <h3>Contact Details</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse eget erat magna. Pellentesque justo
                                    ante</p>
                                <br>
                                <p><i class="fa fa-home info"></i> 1102 Saint Marys, Junction City, KS </p>
                                <p><i class="fa fa-phone info"></i> +(501) 223-6590</p>
                                <p><i class="fa fa-envelope-o info"></i> business.email@bz.com</p>
                                <p><i class="fa fa-globe info"></i> www.business.webpage.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        ';
        return $html;
    }   
    public function aboutUs(){
        $html = '
            '.$this->banner('About Us',
            '<li class="breadcrumb-item text-white" aria-current="page">About Us</li>'
            ).'
            
            <!-- Main container start -->
            <section id="main-container">
                <div class="container">
            
                    <!-- Company Profile -->
                    <div class="row">
                        <div class="col-md-12 heading">
                            <span class="title-icon classic float-left"><i class="fa fa-building"></i></span>
                            <h2 class="title classic">ExportBelize</h2>
                        </div>
                    </div><!-- Title row end -->
            
                    <div class="row landing-tab">
                        <div class="col-md-3 col-sm-5">
                            <div class="nav flex-column nav-pills border-right" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <a class="animated fadeIn nav-link py-4 active d-flex align-items-center" data-toggle="pill" href="#tab_1"
                                    role="tab" aria-selected="true">
                                    <i class="fa fa-info-circle mr-4"></i>
                                    <span class="h4 mb-0 font-weight-bold">Who Are We</span>
                                </a>
                                <a class="animated fadeIn nav-link py-4 d-flex align-items-center" data-toggle="pill" href="#tab_3" role="tab"
                                    aria-selected="true">
                                    <i class="fa fa-eye mr-4"></i>
                                    <span class="h4 mb-0 font-weight-bold">Our Vision</span>
                                </a>
                                <a class="animated fadeIn nav-link py-4 d-flex align-items-center" data-toggle="pill" href="#tab_2" role="tab"
                                    aria-selected="true">
                                    <i class="fa fa-bullseye mr-4"></i>
                                    <span class="h4 mb-0 font-weight-bold">Our Mission</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-9 col-sm-7">
                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane pl-sm-5 fade show active animated fadeInLeft" id="tab_1" role="tabpanel">
                                    <h3>We Are EXPORTBelize</h3>
                                    <p>A unit of the Belize Trade and Investment Development Service (BELTRAIDE), 
                                    which is a statutory body of the Government of Belize under the Ministry of Economic Development,
                                    Petroleum, Investment, Trade & Commerce. EXPORTBelize provides customized needs based services in
                                    the areas of export development and promotion.</p>
                                </div>
                                <div class="tab-pane pl-sm-5 fade animated fadeInLeft" id="tab_3" role="tabpanel">
                                    <h3>Our Vision is</h3>
                                    <p>Enabling a dynamic and competitive export sector that is founded on principles of quality, innovation
                                    and customer orientation.</p>
                                </div>
                                <div class="tab-pane pl-sm-5 fade animated fadeInLeft" id="tab_2" role="tabpanel">
                                    <h3>Our Mission is</h3>
                                    <p>To foster an enabling environment that promotes diversification and competitiveness of Belize’s export sector.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Content row end -->
                </div>
                <!--/ 1st container end -->
            
            
                <div class="gap-60"></div>
            
            
                <!-- Counter Strat -->
                <div class="ts_counter_bg parallax parallax2">
                    <div class="parallax-overlay"></div>
                    <div class="container">
                        <div class="row wow fadeInLeft text-center">
                            <div class="facts col-md-3 col-sm-6">
                                <span class="facts-icon"><i class="fa fa-user"></i></span>
                                <div class="facts-num">
                                    <span class="counter">1200</span>
                                </div>
                                <h3>Clients</h3>
                            </div>
            
                            <div class="facts col-md-3 col-sm-6">
                                <span class="facts-icon"><i class="fa fa-institution"></i></span>
                                <div class="facts-num">
                                    <span class="counter">1277</span>
                                </div>
                                <h3>Item Sold</h3>
                            </div>
            
                            <div class="facts col-md-3 col-sm-6">
                                <span class="facts-icon"><i class="fa fa-suitcase"></i></span>
                                <div class="facts-num">
                                    <span class="counter">869</span>
                                </div>
                                <h3>Projects</h3>
                            </div>
            
                            <div class="facts col-md-3 col-sm-6">
                                <span class="facts-icon"><i class="fa fa-trophy"></i></span>
                                <div class="facts-num">
                                    <span class="counter">76</span>
                                </div>
                                <h3>Awwards</h3>
                            </div>
            
                            <div class="gap-40"></div>
            
                            <!--<div class="col-12 text-center"><a href="#" class="btn btn-primary solid">See Our Portfolio</a></div>-->
            
                        </div>
                        <!--/ row end -->
                    </div>
                    <!--/ Container end -->
                </div>
                <!--/ Counter end -->
            
                <div class="gap-60"></div>
            
            
                <div class="container">
                    <!-- 2nd container start -->
            
                    <!-- Team start -->
                    <div class="team">
            
                        <div class="row">
                            <div class="col-md-12 heading">
                                <span class="title-icon classic float-left"><i class="fa fa-weixin"></i></span>
                                <h2 class="title classic">Meet with our Team</h2>
                            </div>
                        </div><!-- Title row end -->
            
                        <div class="row text-center">
                            <div class="col-md-3 col-sm-6">
                                <div class="team wow slideInLeft">
                                    <div class="img-hexagon">
                                        <img src="images/team/team1.jpg" alt="">
                                        <span class="img-top"></span>
                                        <span class="img-bottom"></span>
                                    </div>
                                    <div class="team-content">
                                        <h3>Full Name</h3>
                                        <p>Job Title</p>
                                        <div class="team-social">
                                            <a class="fb" href="#"><i class="fa fa-facebook"></i></a>
                                            <a class="twt" href="#"><i class="fa fa-twitter"></i></a>
                                            <a class="linkdin" href="#"><i class="fa fa-linkedin"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Team 1 end -->
                            <div class="col-md-3 col-sm-6">
                                <div class="team wow slideInLeft">
                                    <div class="img-hexagon">
                                        <img src="images/team/team2.jpg" alt="">
                                        <span class="img-top"></span>
                                        <span class="img-bottom"></span>
                                    </div>
                                    <div class="team-content">
                                        <h3>Full Name</h3>
                                        <p>Job Title</p>
                                        <div class="team-social">
                                            <a class="fb" href="#"><i class="fa fa-facebook"></i></a>
                                            <a class="twt" href="#"><i class="fa fa-twitter"></i></a>
                                            <a class="linkdin" href="#"><i class="fa fa-linkedin"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Team 2 end -->
                            <div class="col-md-3 col-sm-6">
                                <div class="team wow slideInRight">
                                    <div class="img-hexagon">
                                        <img src="images/team/team3.jpg" alt="">
                                        <span class="img-top"></span>
                                        <span class="img-bottom"></span>
                                    </div>
                                    <div class="team-content">
                                        <h3>Full Name</h3>
                                        <p>Job Title</p>
                                        <div class="team-social">
                                            <a class="fb" href="#"><i class="fa fa-facebook"></i></a>
                                            <a class="twt" href="#"><i class="fa fa-twitter"></i></a>
                                            <a class="linkdin" href="#"><i class="fa fa-linkedin"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Team 3 end -->
                            <div class="col-md-3 col-sm-6">
                                <div class="team animate wow slideInRight">
                                    <div class="img-hexagon">
                                        <img src="images/team/team4.jpg" alt="">
                                        <span class="img-top"></span>
                                        <span class="img-bottom"></span>
                                    </div>
                                    <div class="team-content">
                                        <h3>Full Name</h3>
                                        <p>Job Title</p>
                                        <div class="team-social">
                                            <a class="fb" href="#"><i class="fa fa-facebook"></i></a>
                                            <a class="twt" href="#"><i class="fa fa-twitter"></i></a>
                                            <a class="linkdin" href="#"><i class="fa fa-linkedin"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Team 4 end -->
                        </div>
                        <!--/ Content row end -->
            
                    </div><!-- Team end -->
            
                </div><!-- 2nd container end -->
            </section>
            <!--/ Main container end -->

        ';
        return $html;
    }
    public function pageNotFound(){
        $html = '
       
            '.$this->banner('404 Page not Found', 
            '<li class="breadcrumb-item text-white" aria-current="page">404 Error</li>'
            ).'   

        
        <!-- Main container start -->
        <section id="main-container">
            <div class="container">
                <div class="error-page text-center">
                    <div class="error-code">
                        <strong>404</strong>
                    </div>
                    <div class="error-message">
                        <h3>Oops... Page Not Found!</h3>
                    </div>
                    <div class="error-body">
                        Try using the button below to go to main page of the site <br />
                        <a href="'.BASE_URL.'" class="btn btn-primary solid blank"><i class="fa fa-arrow-circle-left">&nbsp;</i> Go to
                            Home</a>
                    </div>
                </div>
            </div>
        </section>
        <!--/ Main container end -->
        
        ';
        return $html;
    }
    // Displays a signIn section
    public function signIn($message = null){
             
        $html = '
            '.$this->banner('Sign In', 
            '<li class="breadcrumb-item text-white" aria-current="page">Sign In</li>'
            ).'
            <section class="buy-pro" style="padding-top: 20px;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="card shadow" >
                                <div class="card-body">
                                    <h3 class="card-title">Welcome Back!</h3>
                                    '.($message ?? '').'
                                    <form action="'.BASE_URL.'index.php" method="POST">
                                        <input type="hidden" name="action" value="signIn">
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                            <input type="email" class="form-control" name="email" id="inputEmail3" placeholder="Email" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                                            <div class="col-sm-10">
                                            <input type="password" name="password" class="form-control" id="pass" placeholder="Password" required>
                                            </div>
                                        </div>                                      
                                        <div class="form-group row">
                                            <div class="col-8 offset-sm-2">
                                                <input type="checkbox" onclick="myFunction()"> Show Password
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <span class="offset-sm-2">
                                                    <a href="'.BASE_URL.'index.php?page=buyerRegistration">Register as a Buyer</a> OR
                                                    <a href="'.BASE_URL.'index.php?page=companyRegistration">Register as a Company</a>
                                                    <br>
                                                </span>
                                                <span class="offset-sm-2">
                                                    <a class="card-link" href="'.BASE_URL.'index.php?page=forgotPassword" >Forgot Password?</a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary float-right">Sign in</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </section>

        ';
        return $html;
    }
    //displays user profile and company profile
    public function companyProfile($data = null){

        $socialOptions = '';
        $socialMediaList = '';
        $exportMarketFields = '';
        $exportMarketOptions = '';
        $count = 1;

        if (isset($data['exportMarkets'])){
            
            //getting all export market options
            foreach($data['exportMarkets'] as $array){
                $exportMarketOptions .= '<option value="'.$array['id'].'">'.$array['name'].'</option>';
            }

        }
        if (!empty($data['exportMarketList'])){

            //getting all business selected export markets
            foreach($data['exportMarketList'] as $exportMarketList){
                
                $selectOptions = '';
                $exportMarketListId = '';

                foreach($data['exportMarkets'] as $exportMarket){
                    if ($exportMarketList['export_market_id'] == $exportMarket['id']){
                        $exportMarketListId = $exportMarketList['id'];
                        $selectOptions .= '
                            <option value="'.$exportMarket['id'].'" selected>'.$exportMarket['name'].'</option>
                        ';
                    }else{
                        $selectOptions .= '
                            <option value="'.$exportMarket['id'].'">'.$exportMarket['name'].'</option>
                        ';

                    }
                }
                $exportMarketFields .= '
                    
                    <div class="col-md-6 col-12 mb-3">
                        <input type="hidden" name="exportMarkets['.$count.'][exportMarketListId]" value="'.$exportMarketListId.'">
                        <input type="hidden" name="exportMarkets['.$count.'][companyId]" value="'.$data['companyDetails'][0]['id'].'">
                        <label for="">Export Market <sub id="export-market-'.$count.'">#'.$count.'</sub></label>
                        <div class="input-group">
                            <select name="exportMarkets['.$count.'][exportMarketId]" class="form-control">
                            '.$selectOptions.'
                            </select>
                            <div class="input-group-append">
                                <button class="remove-export-market btn btn-danger" value="'.$exportMarketListId.'"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>  
                    </div>
                ';
                $count++;
            }
        }
        
        if (isset($data['socialContacts'])){
            $index = 0;
            foreach($data['socialContacts'] as $socialContact){
                $id = 0;
                $val = '';
                foreach($data['socialContactList'] as $socialContactList){
                    if($socialContact['id'] == $socialContactList['id']){
                        $val = $socialContactList['link'];
                        $id = $socialContactList['id'];
                    }
                }
                $socialOptions .= '
                <div class="col-md-6 col-12 mb-3">
                    <label for="socialContact">'.$socialContact['name'].' Link <sub>(Optional)</sub></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="'.$socialContact['icon'].' fa-lg"></i></span>
                        </div>
                        <input type="hidden" name="socialContacts['.$index.'][socialContactListId]" value="'.$id.'">
                        <input type="hidden" name="socialContacts['.$index.'][companyId]" value="'.$data['companyDetails'][0]['id'].'">
                        <input type="hidden" name="socialContacts['.$index.'][socialContactId]" value="'.$socialContact['id'].'">
                        <input type="text" class="form-control" placeholder="Enter your '.$socialContact['name'].' link" name="socialContacts['.$index.'][link]" aria-label="SocialLink" aria-describedby="socialMediaLink" value="'.($val ?? '').'">
                    </div>
                </div>
                ';
                $index++;
            }
        }
     
        //spliting full name
        $fullName = explode(' ',$_SESSION['USERDATA']['full_name']);

        $html = $this->banner('My Profile', 
            '<li class="breadcrumb-item text-white" aria-current="page">Profile</li>'
        ).'
            <section class="buy-pro" style="padding-top: 20px;">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-4 mb-3 mb-md-0">
                            <div class="card shadow" >
                                    <button id="editMyProfile" class="btn btn-secondary">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <button id="saveMyProfile" class="btn btn-success" style="display: none;">
                                        <i class="fa fa-save"></i> Save Changes
                                    </button>
                                <div class="card-body">
                                    <h3 class=""><i class="fa fa-user fa-lg title-icon text-primary"></i> Personal Profile</h3>
                                    <form id="myProfile" action="'.BASE_URL.'" method="POST">
                                        <input type="hidden" name="ajaxRequest" value="saveMyProfile">
                                        <div class="form-row">
                                            <div class="col-12 mb-3">
                                            <label for="validationDefault01">First name</label>
                                            <input type="text" class="form-control" id="" name="firstName" placeholder="Enter your First name..." value="'.$fullName[0].'" required>
                                            </div>
                                            <div class="col-12 mb-3">
                                            <label for="validationDefault02">Last name</label>
                                            <input type="text" class="form-control" id="" name="lastName" placeholder="Enter your last name..." value="'.$fullName[1].'" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-12 mb-3">
                                                <label for="email">Email</label>
                                                <div class="input-group">
                                                    <p>'.$_SESSION['USERDATA']['email'].'</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--<div class="form-row">
                                            <div class="col-12 mb-3">
                                                <label for="">Intrest <sub>#1<sub></label>
                                                <select class="form-control">
                                                    <option selected>Open this select menu</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label for="">Intrest <sub>#2<sub></label>
                                                <select class="form-control">
                                                    <option selected>Open this select menu</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label for="">Intrest <sub>#3<sub></label>
                                                <select class="form-control">
                                                    <option selected>Open this select menu</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select>
                                            </div>
                                        </div>-->    
                                        <!--<div class="form-group">
                                            <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                                            <label class="form-check-label" for="invalidCheck2">
                                                Agree to terms and conditions
                                            </label>
                                            </div>
                                        </div>-->
                                    </form>
                                </div>
                            </div>
                        </div> 
                        <div class="col-12 col-md-8">
                            <div class="card shadow" >
                                <button class="btn btn-secondary" id="editCompanyProfile">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-success" id="saveCompanyProfile" style="display: none;">
                                    <i class="fa fa-save"></i> Save Changes
                                </button>
                                <div class="card-body">
                                    <h3 class="card-title"><i class="fa fa-building fa-lg text-primary"></i> Company Profile</h3>
                                    <form id="myCompanyProfile" action="'.BASE_URL.'index.php" method="POST">
                                        <input type="hidden" name="ajaxRequest" value="saveCompanyProfile">
                                        <input type="hidden" name="companyDetail[companyId]" value="'.$data['companyDetails'][0]['id'].'">
                                        <div class="form-row">
                                            <div class="col-12 mx-auto text-center ">
                                                
                                                <input type="hidden" name="logoPath" value="'.((isset($data['companyDetails'][0]['logo_img_path']))? $data['companyDetails'][0]['logo_img_path']: './images/business_icon.png' ) .'">
                                                <img id="business-logo" src="'.((isset($data['companyDetails'][0]['logo_img_path']))? $data['companyDetails'][0]['logo_img_path']: './images/business_icon.png' ) .'" class="avatar rounded img-thumbnail" height="250px" width="200px" >
                                                <div class="p-image">
                                                    <a class="btn btn-link" href="#" id="upload-business-logo">
                                                    <i class="fa fa-upload"></i> Upload Logo
                                                    </a>
                                                    <a href="#" id="remove-company-logo" class="btn btn-link text-danger disabled" style="'.((isset($data['companyDetails'][0]['logo_img_path']))? '': 'display: none' ) .'" >
                                                    <i class="fa fa-trash"></i> Remove Image
                                                    </a>
                                                    <input class="file-upload" name="businessLogo" type="file" accept="image/*" style="display: none;"/>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <h4 class="text-dark pb-0">Company Info</h3>
                                        <hr>
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <label for="validationDefault01">Company Name</label>
                                                <input type="text" class="form-control" id="" name="companyDetail[name]" placeholder="Enter your company name..." value="'.($data['companyDetails'][0]['name'] ?? '').'" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="validationDefault02">Company Contact Email<sub>(Optional)</sub></label>
                                                <input type="text" class="form-control" id="" name="companyDetail[email]" placeholder="Enter your company contact email..." value="'.($data['companyDetails'][0]['email'] ?? '').'" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-12 col-md-6 mb-3">
                                                <label for="">District</label>
                                                <select name="companyDetail[district]" class="form-control">
                                                    <option value="Corozal" '.(($data['companyDetails'][0]['district'] == 'Corozal')? 'selected' : '').'>Corozal</option>
                                                    <option value="Orange Walk" '.(($data['companyDetails'][0]['district'] == 'Orange Walk')? 'selected' : '').'>Orange Walk</option>
                                                    <option value="Belize" '.(($data['companyDetails'][0]['district'] == 'Belize')? 'selected' : '').'>Belize</option>
                                                    <option value="Cayo" '.(($data['companyDetails'][0]['district'] == 'Cayo')? 'selected' : '').' >Cayo</option>
                                                    <option value="Stann Creek" '.(($data['companyDetails'][0]['district'] == 'Stann Creek')? 'selected' : '').'>Stann Creek</option>
                                                    <option value="Toledo" '.(($data['companyDetails'][0]['district'] == 'Toldeo')? 'selected' : '').'>Toledo</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-12 mb-3">
                                                <label for="email">City/Village/Town</label>
                                                <div class="input-group">
                                                    <input type="text" name="companyDetail[ctv]" class="form-control" value="'.($data['companyDetails'][0]['ctv'] ?? '').'" placeholder="Enter your company..." aria-describedby="inputGroupPrepend2" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12 mb-3">
                                                <label for="email">Street</label>
                                                <div class="input-group">
                                                    <input type="text" name="companyDetail[street]" class="form-control" id="" value="'.($data['companyDetails'][0]['street'] ?? '').'" placeholder="Enter your company\'s street address..." aria-describedby="inputGroupPrepend2" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12 mb-3">
                                                <label for="email">Phone Number</label>
                                                <div class="input-group">
                                                    <input type="text" name="companyDetail[phone]" class="form-control" value="'.($data['companyDetails'][0]['phone'] ?? '').'" placeholder="Enter your company\'s phone number..." aria-describedby="inputGroupPrepend2" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12 mb-3">
                                                <label for="email">Company Website Link <sub>(Optional)</sub></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="companyDetail[website]" value="'.($data['companyDetails'][0]['website_link'] ?? '').'" placeholder="Enter an company website link..." aria-describedby="inputGroupPrepend2" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-12 mb-3">
                                                <label for="email">Company Description</label>
                                                <div class="input-group">
                                                <textarea class="form-control" name="companyDetail[description]">'.($data['companyDetails'][0]['description'] ?? '').'</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        '.(($socialOptions != '')? '<h4 class="text-dark pb-0">Social Contact Links</h3><hr><div class="form-row">'.$socialOptions.'</div>' : '').' 
                                        <h4 class="text-dark pb-0">Export Markets</h3>
                                        <hr>
                                        <div class="form-row" id="export-market-list">
                                        '.(($exportMarketFields != '')? ''.$exportMarketFields.'' :
                                        '
                                        
                                            <div class="col-md-6 col-12 mb-3">
                                                <label for="">Export Market <sub id="export-market-'.$count.'">#'.$count.'</sub></label>
                                                <div class="input-group">
                                                    <input type="hidden" name="exportMarkets['.$count.'][companyId]" value="'.$data['companyDetails'][0]['id'].'">
                                                    <select name="exportMarkets['.$count.'][exportMarketId]" class="form-control">
                                                    '.$exportMarketOptions.'
                                                    </select>
                                                    <div class="input-group-append">
                                                        <button class="remove-export-market btn btn-danger"><i class="fa fa-minus"></i></button>
                                                    </div>
                                                </div>  
                                            </div>

                                    
                                        ').' 
                                        </div>
                                            <a href="#" id="add-export-market" class="btn btn-link float-right"><i class="fa fa-plus"> Add More Markets</i>
                                    </form>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
            </section>
            <script>
                var exportMarketOptions = \''.$exportMarketOptions.'\';
                var marketOptionCount = '.$count.';
                var companyId = '.$data['companyDetails'][0]['id'].';
            </script>
        ';
        return $html;
    }
   //displays buyer's profile
    public function buyerProfile($data = null){
       
        $interestSelect = '';
        $hiddenInterestIds = '';
        $sectorOptions = '';
        $fullName = explode(' ',$_SESSION['USERDATA']['full_name']);
        $num = 1;

        //building interest select with options 
        while($num <= 3){
            $sectorOptions = '';
            if (array_key_exists(($num - 1), $data['interest'])){
                
                $defaultInterest = '';
                
                foreach ($data['sectors'] as $sector){
                    
                    if($data['interest'][($num - 1)]['sector_id'] == $sector['id']){
                        $hiddenInterestIds .= '<input type="hidden" name = "interest['.($num-1).'][interestId]" value="'.$data['interest'][($num-1)]['id'].'">';
                        $sectorOptions .= '
                            <option value="'.$sector['id'].'" selected>'.$sector['name'].'</option>
                        ';
                        
                    }else{  

                        $sectorOptions .= '
                            <option value="'.$sector['id'].'">'.$sector['name'].'</option>
                        ';
                    }
                }

            }else{
                // $defaultInterest = 'selected';
                foreach ($data['sectors'] as $sector){
                    $sectorOptions .= '
                        <option value="'.$sector['id'].'">'.$sector['name'].'</option>
                    ';
                }

            }
            $interestSelect .= '
                <div class="col-md-4 col-12 mb-3">
                    <label for="">Intrest <sub>#'.$num.'<sub></label>
                    <select name="interest['.($num-1).'][sectorId]"  class="form-control">
                        <option value="0">None</option>
                        '.$sectorOptions.'
                    </select>
                </div>
            ';
            $num++;
        }

        $html = $this->banner('My Profile', 
            '<li class="breadcrumb-item text-white" aria-current="page">Profile</li>'
        ).'
            <section class="buy-pro" style="padding-top: 20px;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 mx-auto">
                            <div class="card shadow" >
                                    <button id="editMyProfile" class="btn btn-secondary">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <button id="saveMyProfile" class="btn btn-success" style="display: none;">
                                        <i class="fa fa-save"></i> Save Changes
                                    </button>
                                <div class="card-body">
                                    <h3 class=""><i class="fa fa-user fa-lg title-icon text-primary"></i> Personal Profile</h3>
                                    <form id="myProfile" action="'.BASE_URL.'">
                                        '.($data['message'] ?? '').'
                                        <input type="hidden" name="ajaxRequest" value="saveBuyerProfile">
                                        '.$hiddenInterestIds.'
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                            <label for="validationDefault01">First name</label>
                                            <input type="text" class="form-control" id="" name="firstName" placeholder="Enter your first name..." value="'.$fullName[0].'" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                            <label for="validationDefault02">Last name</label>
                                            <input type="text" class="form-control" id="" name="lastName" placeholder="Enter your last name..." value="'.$fullName[1].'" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6 col-12 mb-3">
                                                <label for="email">Email</label>
                                                <div class="input-group">
                                                    <p class="pt-2">'.$_SESSION['USERDATA']['email'].'</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12 mb-3">
                                                <label for="email">Company Name</label>
                                                <div class="input-group">
                                                    <input type="text" name="companyName" class="form-control" value="'.$data['companyDetails'][0]['name'].'" placeholder="Enter your company..." aria-describedby="inputGroupPrepend2" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            '.$interestSelect.'                                          
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </section>
        ';
        return $html;
    }  
    // displays admin profile
    public function adminProfile(){
        $html = $this->banner('My Profile', 
            '<li class="breadcrumb-item text-white" aria-current="page">Profile</li>'
        ).'
            <section class="buy-pro" style="padding-top: 20px;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 mx-auto">
                            <div class="card shadow" >
                                    <button id="editMyProfile" class="btn btn-secondary">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <button id="saveMyProfile" class="btn btn-success" style="display: none;">
                                        <i class="fa fa-save"></i> Save Changes
                                    </button>
                                <div class="card-body">
                                    <h3 class=""><i class="fa fa-user fa-lg title-icon text-primary"></i> Personal Profile</h3>
                                    <form id="myProfile">
                                        <input type="hidden" name="action" value="buyerRegistration">
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                            <label for="validationDefault01">First name</label>
                                            <input type="text" class="form-control" id="" name="firstName" placeholder="Enter your First name..." value="" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                            <label for="validationDefault02">Last name</label>
                                            <input type="text" class="form-control" id="" name="lastName" placeholder="Enter your last name..." value="" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-12 col-12 mb-3">
                                                <label for="email">Email</label>
                                                <div class="input-group">
                                                    <p>yourname@someemail.com</p>
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <!--<div class="form-group">
                                            <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                                            <label class="form-check-label" for="invalidCheck2">
                                                Agree to terms and conditions
                                            </label>
                                            </div>
                                        </div>-->
                                    </form>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </section>
        ';
        return $html;
    }
    public function companyRegistrationForm(){
        $html = '
            '.$this->banner('Company Registration Form',
                '<li class="breadcrumb-item text-white" aria-current="page">Registration Form</li>'
             ).' 
                <section class="buy-pro" style="padding-top: 20px;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="card shadow" >
                                <div class="card-body">
                                    <h3 class="card-title">Company Registration Form</h3>
                                    <form action="'.BASE_URL.'index.php" method="POST">
                                        <input type="hidden" name="action" value="companyRegistration">
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                            <label for="validationDefault01">First name</label>
                                            <input type="text" class="form-control" id="validationDefault01" name="firstName" placeholder="Enter your First name..." value="" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                            <label for="validationDefault02">Last name</label>
                                            <input type="text" class="form-control" id="validationDefault02" name="lastName" placeholder="Enter your last name..." value="" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6 col-12 mb-3">
                                                <label for="email">Business Email</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="email" id="buyerEmail" placeholder="Enter business email..." aria-describedby="inputGroupPrepend2" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12 mb-3">
                                                <label for="email">Business Name</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="businessName" id="buyerEmail" placeholder="Enter business name..." aria-describedby="inputGroupPrepend2" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-4 col-12 mb-3">
                                                <label for="email">City/Town/Village</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="ctv" id="buyerEmail" placeholder="Enter business name..." aria-describedby="inputGroupPrepend2" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12 mb-3">
                                                <label for="email">Street</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="street" id="buyerEmail" placeholder="Enter business name..." aria-describedby="inputGroupPrepend2" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12 mb-3">
                                                <label for="email">District</label>
                                                <select class="form-control" name="district">
                                                    <option vlaue="Corozal" selected>Corozal</option>
                                                    <option value="Orange Walk">Orange Walk</option>
                                                    <option value="Belize">Belize</option>
                                                    <option value="Cayo">Cayo</option>
                                                    <option value="Stann Creek">Stann Creek</option>
                                                    <option value="Toledo">Toledo</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6 col-12 mb-3">
                                            <label for="validationDefault03">New Password</label>
                                            <input type="text" name="newPass" class="form-control" id="newPass" placeholder="Enter a new password" required>
                                            </div>
                                            <div class="col-md-6 col-12 mb-3">
                                            <label for="validationDefault03">Confirm Password</label>
                                            <input type="text" class="form-control" name="confirmPass" id="confirmPass" placeholder="Re-enter new password..." required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <a href="'.BASE_URL.'index.php?page=signIn" >I have an Account</a>
                                        </div>
                                        <!--<div class="form-group">
                                            <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                                            <label class="form-check-label" for="invalidCheck2">
                                                Agree to terms and conditions
                                            </label>
                                            </div>
                                        </div>-->
                                        <button class="btn btn-primary float-right" type="submit">Submit form</button>
                            
                                    </form>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </section>
        ';
        return $html;
    }
    public function buyerRegistrationForm($data = null){

        $sectorOptions = '';

        foreach ($data['sectors'] as $sector){
            $sectorOptions .= '
                <option value="'.$sector['id'].'">'.$sector['name'].'</option>
            ';
        }

        $html = '
            '.$this->banner('Buyer Registration Form', 
                '<li class="breadcrumb-item text-white" aria-current="page">Registration Form</li>'
            ).'  

            <section class="buy-pro" style="padding-top: 20px;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="card shadow" >
                                <div class="card-body">
                                    <h3 class="card-title">Buyer Registration Form</h3>
                                    <form action="'.BASE_URL.'" method="POST">
                                        '.($data['message'] ?? '').'
                                        <input type="hidden" name="action" value="buyerRegistration">
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                            <label for="firstName">First name</label>
                                            <input type="text" class="form-control" id="" name="firstName" placeholder="Enter your First name..." value="" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                            <label for="validationDefault02">Last name</label>
                                            <input type="text" class="form-control" id="" name="lastName" placeholder="Enter your last name..." value="" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6 col-12 mb-3">
                                                <label for="email">Email</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="email" id="" placeholder="Enter an email..." aria-describedby="inputGroupPrepend2" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12 mb-3">
                                                <label for="email">Company Name</label>
                                                <div class="input-group">
                                                    <input type="text" name="companyName" class="form-control" id="" placeholder="Enter your company..." aria-describedby="inputGroupPrepend2" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-4 col-12 mb-3">
                                                <label for="">Intrest <sub>#1<sub></label>
                                                <select name="interest[][sectorId]"  class="form-control">
                                                    <option value="0" selected>None</option>
                                                    '.$sectorOptions.'
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-12 mb-3">
                                                <label for="">Intrest <sub>#2<sub></label>
                                                <select name="interest[][sectorId]" class="form-control">
                                                    <option value="0" selected>None</option>
                                                    '.$sectorOptions.'
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-12 mb-3">
                                                <label for="">Intrest <sub>#3<sub></label>
                                                <select name="interest[][sectorId]" class="form-control">
                                                    <option value="0" selected>None</option>
                                                    '.$sectorOptions.'
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                                                    <i class="fa fa-info-circle fa-lg"></i> Password should contain 8 characters minimum.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            
                                            <div class="col-md-6 col-12 mb-3">
                                                <label for="validationDefault03">New Password</label>
                                                <input type="password" name="newPass" class="form-control" id="newPass" placeholder="Enter a new password" minlength="8" required>
                                            </div>
                                            <div class="col-md-6 col-12 mb-3">
                                                <label for="validationDefault03">Confirm Password</label>
                                                <input type="password" class="form-control" name="confirmPass" id="confirmPass" placeholder="Re-enter new password..." minlength="8" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-12 ">
                                                <span class="float-left">
                                                    <a href="'.BASE_URL.'index.php?page=signIn" class="float-left">I have an Account</a>
                                                </span>
                                                <span class="float-right">
                                                    <input type="checkbox" onclick="displayBothPasswords()"> Show Password
                                                </span>
                                            </div>
                                        </div>
                                        <br>
                                        
                                        <!--<div class="form-group">
                                            <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                                            <label class="form-check-label" for="invalidCheck2">
                                                Agree to terms and conditions
                                            </label>
                                            </div>
                                        </div>-->
                                        <button class="btn btn-primary float-right" type="submit">Submit form</button>
                                    </form>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </section>
        ';
        return $html;
    }
    public function thankYou(){
        $html = '
            '.$this->banner('Thank you for ',
                '<li class="breadcrumb-item text-white" aria-current="page">Thank You</li>'
             ).'
            <section class="buy-pro" style="padding-top: 20px;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="card shadow" >
                                <div class="card-body">
                                    <h3 class="card-title">Welcome Back!</h3>
                                    <form action="'.BASE_URL.'index.php" method="POST">
                                        <input type="hidden" name="action" value="signIn">
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                            <input type="email" class="form-control" name="email" id="inputEmail3" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                                            <div class="col-sm-10">
                                            <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Password">
                                            </div>
                                        </div>                                      
                                        <div class="form-group row">
                                            <div class="col-sm-10">
                                                <a class="card-link" href="'.BASE_URL.'index.php?page=forgotPassword" >Forgot Password?</a>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary float-right">Sign in</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </section>

        ';
        return $html;

    }
    public function forgotPassword(){
        $html = '
            '.$this->banner('Thank you for ').'
            <section class="buy-pro" style="padding-top: 20px;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="card shadow" >
                                <div class="card-body">
                                    <h3 class="card-title">Welcome Back!</h3>
                                    <form action="'.BASE_URL.'index.php" method="POST">
                                        <input type="hidden" name="action" value="signIn">
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                            <input type="email" class="form-control" name="email" id="inputEmail3" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                                            <div class="col-sm-10">
                                            <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Password">
                                            </div>
                                        </div>                                      
                                        <div class="form-group row">
                                            <div class="col-sm-10">
                                                <a class="card-link" href="'.BASE_URL.'index.php?page=forgotPassword" >Forgot Password?</a>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary float-right">Sign in</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </section>

        ';
        return $html;

    }
    public function contact(){
        $html = '
            '.$this->banner('Contact Us', 
                '<li class="breadcrumb-item text-white" aria-current="page">Contact Us</li>'
            ).'   

        <!-- Main container start -->
        <section id="main-container">
            <div class="container">
                <!-- Map start here -->
                <div class="map" id="map_canvas" data-latitude="51.507351" data-longitude="-0.127758"
                    data-marker="images/marker.png"></div>
                <!--/ Map end here -->

                <div class="gap-40"></div>

                <div class="row">
                    <div class="col-md-5">
                        <div class="contact-info">
                            <h3>Contact Details</h3>
                            <br>
                            <p><i class="fa fa-home info"></i> 3401 Mountain View Blvd., Suite 201, Belmopan, Cayo, ​Belize, Central America </p>
                            <p><i class="fa fa-phone info"></i> +(785) 238-4131 </p>
                            <p><i class="fa fa-envelope-o info"></i> beltraide@belizeinvest.org.bz</p>
                            <p><i class="fa fa-globe info"></i> <a href="https://www.belizeinvest.org.bz/exportbelize.html">Learn More About Us!</a></p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card shadow" >
                            <div class="card-body">
                                <h3 class="card-title">Connect With Us!</h3>

                                <form id="contact-form" action="contact-form.php" method="post" role="form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input class="form-control" name="name" id="name" placeholder="" type="text" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input class="form-control" name="email" id="email" placeholder="" type="email" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Subject</label>
                                                <input class="form-control" name="subject" id="subject" placeholder="" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Message</label>
                                        <textarea class="form-control" name="message" id="message" placeholder="" rows="10" required></textarea>
                                    </div>
                                    <div class="text-right"><br>
                                        <button class="btn btn-primary solid blank" type="submit">Send Message</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ container end -->
        </section>
        <!--/ Main container end -->

        ';
        return $html;
    }
    public function footer(){
        $html = '
        <!-- Footer start -->
        <section id="footer" class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="footer-logo">
                            <a href="https://www.belizeinvest.org.bz/" ><img src="'.BASE_URL.'images/beltraide-logo-full-color_1.png" alt="logo"></a>
                        </div>
                        <div class="gap-20"></div>
                        <ul class="dark unstyled">
                            <li>
                                <a title="Facebook" href="https://www.facebook.com/BELTRAIDE/">
                                    <span class="icon-pentagon wow bounceIn"><i class="fab fa-facebook-f fa-lg"></i></span>
                                </a>
                                <a title="Twitter" href="https://twitter.com/Beltraide">
                                    <span class="icon-pentagon wow bounceIn"><i class="fab fa-twitter"></i></span>
                                </a>
                                <a title="Google+" href="https://instagram.com/beltraide/">
                                    <span class="icon-pentagon wow bounceIn"><i class="fab fa-instagram fa-lg"></i></span>
                                </a>
                                <a title="Google+" href="https://www.linkedin.com/company/beltraide/">
                                    <span class="icon-pentagon wow bounceIn"><i class="fab fa-linkedin-in fa-lg"></i></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--/ Row end -->
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="copyright-info">
                            &copy; Copyright '.date('Y').' BELTRAIDE </span>
                        </div>
                    </div>
                </div>
                <!--/ Row end -->
                <div id="back-to-top" data-spy="affix" data-offset-top="10" class="back-to-top affix position-fixed">
                    <button class="btn btn-primary" title="Back to Top"><i class="fa fa-angle-double-up"></i></button>
                </div>
            </div>
            <!--/ Container end -->
        </section>
        <!--/ Footer end -->
    
        </div><!-- Body inner end -->
        <script> 
            //Global Variables for JS
            var BASE_URL = "'.BASE_URL.'";
        
        </script>
        <!-- jQuery -->
        <script src="'.BASE_URL.'plugins/jQuery/jquery.min.js"></script>
        
        <!-- Bootstrap JS -->
        <script src="'.BASE_URL.'plugins/bootstrap/bootstrap.min.js"></script>

        <!-- Style Switcher -->
        <script type="text/javascript" src="'.BASE_URL.'plugins/style-switcher.js"></script>
       
        <!-- Owl Carousel -->
        <script type="text/javascript" src="'.BASE_URL.'plugins/owl/owl.carousel.js"></script>
        
        <!-- PrettyPhoto -->
        <script type="text/javascript" src="'.BASE_URL.'plugins/jquery.prettyPhoto.js"></script>
        
        <!-- Bxslider -->
        <script type="text/javascript" src="'.BASE_URL.'plugins/flex-slider/jquery.flexslider.js"></script>
       
        <!-- CD Hero slider -->
        <script type="text/javascript" src="'.BASE_URL.'plugins/cd-hero/cd-hero.js"></script>
        
        <!-- Isotope -->
        <script type="text/javascript" src="'.BASE_URL.'plugins/isotope.js"></script>
        <script type="text/javascript" src="'.BASE_URL.'plugins/ini.isotope.js"></script>
        
        <!-- Wow Animation -->
        <script type="text/javascript" src="'.BASE_URL.'plugins/wow.min.js"></script>
        
        <!-- Eeasing -->
        <script type="text/javascript" src="'.BASE_URL.'plugins/jquery.easing.1.3.js"></script>
        
        <!-- Counter -->
        <script type="text/javascript" src="'.BASE_URL.'plugins/jquery.counterup.min.js"></script>
        
        <!-- Waypoints -->
        <script type="text/javascript" src="'.BASE_URL.'plugins/waypoints.min.js"></script>
        
        <!-- google map -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcABaamniA6OL5YvYSpB3pFMNrXwXnLwU&libraries=places"></script>
        <script src="'.BASE_URL.'plugins/google-map/gmap.js"></script>
        
        <!-- Datatables JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="'.BASE_URL.'plugins/datatables/jQuery.dataTables.min.js"></script>
        <script src="'.BASE_URL.'plugins/datatables/dataTables.bootstrap4.min.js"></script>
        
        <!--<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>-->
        
        <!-- File Input Master Scripts -->
        <script src="'.BASE_URL.'plugins/bootstrap-fileinput-master/js/plugins/piexif.min.js"></script>
        <script src="'.BASE_URL.'plugins/bootstrap-fileinput-master/js/plugins/sortable.min.js"></script>
        <script src="'.BASE_URL.'plugins/bootstrap-fileinput-master/js/plugins/purify.min.js"></script>

        <!-- File Input Master Script -->
        <script src="'.BASE_URL.'plugins/bootstrap-fileinput-master/js/fileinput.min.js"></script>
        <script src="'.BASE_URL.'plugins/bootstrap-fileinput-master/themes/fas/theme.js"></script>
        <script src="'.BASE_URL.'js/jQuery-file-upload.js"></script>
        
        
        <!-- Main Script -->
        <script src="'.BASE_URL.'js/script.js"></script>
        
        <!-- Datatable custom Script -->
        <script src="'.BASE_URL.'js/datatables.js"></script>
        
        <!-- custom javascript function definition Script -->
        <script src="'.BASE_URL.'js/custom.js"></script>

        
        </body>
        
        </html>
        ';
        return $html;
    }

}
?>