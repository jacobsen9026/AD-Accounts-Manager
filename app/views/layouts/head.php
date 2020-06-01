<?php
/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
?>

<head>
    <title>
        <?php
        echo $this->title;
        ?>
    </title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <link rel="stylesheet" href="/css/style2.css">

    <link rel="stylesheet" href="/css/loaders.css">

    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">


    <?php
    /**
     * Handle Themes
     */
    if (isset($this->app->user)) {
        if ($this->app->user->theme != \app\config\Theme::DEFAULT_THEME and $this->app->user->theme != \app\config\Theme::BLUE_THEME) {
            System\App\AppLogger::get()->debug("theme is " . $this->app->user->theme);
            $theme = explode("_", $this->app->user->theme);
            $theme = $theme[0] . ucfirst($theme[1]);

            echo ' <link rel="stylesheet" href="/css/' . $theme . '.css">';
        }
    }
    /**
     * if ($this->app->user->theme == \app\config\Theme::RED_THEME) {
     * System\App\AppLogger::get()->debug("theme is red");
     * echo ' <link rel="stylesheet" href="/css/redTheme.css">';
     * }
     * if ($this->app->user->theme == \app\config\Theme::GREEN_THEME) {
     * System\App\AppLogger::get()->debug("theme is green");
     * echo ' <link rel="stylesheet" href="/css/greenTheme.css">';
     * }
     *
     */
    ?>


    <script>
        /**
         * Highlight changed items on all forms
         * and show save button if available.
         */



        function preparePageHooks() {
            console.log('Preparing page hooks');

            let handler = function () {
                console.log('key pressed in text box 2'
                    + this
                )
                $(this).addClass('text-danger border-danger');
                /**
                 * Show the save form button
                 */
                $(".floating-form-button").removeClass("show");
                $(".floating-form-button").addClass("show");
            };
            $(document).find('input').change(handler);
            $(document).find('input').keyup(handler);


        }

        $(document).ready(function () {
            preparePageHooks($(document));


        });


        //Custom JQuery to enable data-text-alt tag on btn class objects
        jQuery(function ($) {
            $('.btn[data-toggle="collapse"]').on('click', function () {
                console.log($(this).data('text-alt'));
                $(this).data('text-original', $(this).text());
                $(this).text($(this).data('text-alt'));
                $(this).data('text-alt', $(this).data('text-original'));
            });
            $(document).on('click', '.clickable[data-text-alt]', function () {
                console.log($(this).data('text-alt'));
                $(this).data('text-original', $(this).html());
                $(this).html($(this).data('text-alt'));
                $(this).data('text-alt', $(this).data('text-original'));
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

        $(document).ready(function () {
            $(".resizable-y").resizable({
                    handles:
                        "n, s"
                }
            );
        });
        $(document).ready(function () {
            $(".resizable-x").resizable({
                    handles:
                        "e, w"
                }
            );
        });

    </script>


</head>
