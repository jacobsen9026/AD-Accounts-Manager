<?php
echo $this->view("/layouts/head");

use App\Models\Database\AppDatabase;
?>



</head>


<body class="bg-dark">
    <div class=" container-fluid centered px-0 px-md-5 ">


        <div class='col-xl-5 col-lg-6 col-md-7 col-sm-8 centered text-center text_centered container container-fluid pb-2 px-0 mt-sm-5 mt-md-8 mb-5 mx-auto shadow-lg-hover bg-light'>
            <nav class="shadow navbar navbar-expand-md bg-secondary navbar-dark mb-3">
                <div>


                    <div class="" id="navbarBrandText">
                        <!-- Brand -->
                        <a class="navbar-brand" href="/">
                            <i class="text-light fas fa-graduation-cap mr-1"></i>
                            <?php echo AppDatabase::getAppName(); ?>
                        </a>
                    </div>


                </div>
            </nav>
            <div class="px-5 ">