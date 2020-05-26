<?php
echo $this->view("/layouts/head");

use App\Models\Database\AppDatabase;

?>


</head>


<body class="bg-dark">
<div class="row h-100 h-sm-auto centered px-0 px-md-5 ">


    <div class='col col-xl-5 col-lg-6 col-md-7 col-sm-8 centered text-center text_centered container container-fluid pb-0 pb-sm-2 px-0 mt-sm-5 mt-md-8 mb-0 mb-sm-5 mx-auto shadow bg-light'>
        <nav class="shadow navbar navbar-expand-md bg-secondary navbar-dark mb-3">
            <div>


                <div class="pl-2" id="navbarBrandText">
                    <!-- Brand -->
                    <a class="navbar-brand" href="/">
                        <i class="text-light fas fa-graduation-cap mr-1"></i>
                        <?php echo AppDatabase::getAppName(); ?>
                    </a>
                </div>


            </div>
        </nav>
        <div class="px-5 mt-5 mt-sm-0 pt-5 pt-sm-2">
