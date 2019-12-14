<html lang="en">
    <head>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

        <link href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" rel="stylesheet">
        <script src="//code.jquery.com/jquery-1.11.1.js"></script>
        <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>


        <script>
            //Custom JQuery to enable data-text-alt tag on btn class objects
            jQuery(function ($) {
                $('.btn[data-toggle="collapse"]').on('click', function () {
                    $(this)
                            .data('text-original', $(this).text())
                            .text($(this).data('text-alt'))
                            .data('text-alt', $(this).data('text-original'));
                });
            });
            //Custom JQuery for district settings navigation
            jQuery(function ($) {


                // remove active class from all
                var location = window.location.pathname.split('/')[1];


// remove active class from all
                $(".nav .nav-item").removeClass('active');
                console.log('.nav-item a[href=' + location + '*]');
// add active class to div that matches active url
                $(".nav-item a[href='/" + location + "']").addClass('active');
            });
        </script>

        <meta name="theme-color" content="#ffffff">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>