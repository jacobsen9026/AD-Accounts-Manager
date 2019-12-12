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

        <script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>

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


        </script>

        <meta name="theme-color" content="#ffffff">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>