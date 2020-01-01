
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


    <script>
        //Highlight changed items on all forms
        $(document).ready(function () {

            $('input').keyup(function () {
                $(this).addClass('text-danger border-danger');

            });

            $('select').change(function () {
                console.log("wpsdafdsa");
                $(this).addClass('border-danger text-danger');

            });

        });
    </script>



</head>


<body>
    <div class="container-fluid centered px-0 px-md-5">


        <div class='centered text-center text_centered container-fluid  px-0   mt-md-8 mb-5 mx-auto shadow-lg mt-5 pt-2 pt-md-0'>










