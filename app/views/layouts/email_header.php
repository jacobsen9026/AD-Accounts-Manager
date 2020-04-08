
<head>
    <title>
        <?php
        echo $this->title;
        ?>
    </title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <link rel="stylesheet" href="/css/style2.css">

    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">


    <?php
    if ($this->app->user->theme == \app\config\Theme::RED_THEME) {
        system\app\AppLogger::get()->debug("theme is red");
        echo ' <link rel="stylesheet" href="/css/redTheme.css">';
    }
    ?>



</head>


<body class="bg-light">
    <div class=" container-fluid centered px-0 px-md-5">

        <div class=' centered text-center text_centered container container-fluid py-5 px-0 px-md-5 mt-5 mt-md-8 mb-5 mx-auto shadow-lg bg-white'>



