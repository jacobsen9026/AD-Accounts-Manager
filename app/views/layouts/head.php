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

        use App\Config\Theme;
        use App\Models\View\Javascript;

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
    <link rel="manifest" href="/mobile/manifest.json">
    <link rel="manifest" href="/mobile/manifest.json">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">


    <?php
    /**
     * Handle Themes
     */
    if (isset($this->app->user)) {
        if ($this->app->user->theme != Theme::DEFAULT_THEME and $this->app->user->theme != Theme::BLUE_THEME) {
            System\App\AppLogger::get()->debug("theme is " . $this->app->user->theme);
            $theme = explode("_", $this->app->user->theme);
            $theme = $theme[0] . ucfirst($theme[1]);

            echo ' <link rel="stylesheet" href="/css/' . $theme . '.css">';
        }
    }
    ?>


    <script>


        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/js/sw.js', {scope: './'}).then(function (registration) {

                    <?=Javascript::debug('ServiceWorker registration successful')?>;
                }, function (err) {
                    <?=Javascript::debug('ServiceWorker registration failed')?>;
                });
            });
        }


        /**
         * Highlight changed items on all forms
         * and show save button if available.
         */



        function preparePageHooks() {
            <?=Javascript::debug('Preparing page hooks')?>

            let handler = function () {
                <?=Javascript::debug('key pressed')?>
                if (!$('form[name="loginForm"]').length) {

                    $(this).addClass('text-danger border-danger');
                    /**
                     * Show the save form button
                     */
                    $(".floating-form-button").removeClass("show");
                    $(".floating-form-button").addClass("show");
                }

            };
            $(document).find('input').change(handler);
            $(document).find('input').keyup(handler);


            /**
             * Enable Bootstrap Tooltips system-wide
             */
            $(function () {
                $('[data-toggle="tooltip"]').tooltip({boundary: 'window'});
                $('[data-toggle="show"]').on('click', function () {
                    let target = $(this).data('target');
                    console.log(target);
                    $(target).toggleClass('show');
                });
            })


            $(".ui-helper-hidden-accessible").remove();
        }

        $(document).ready(function () {
            preparePageHooks($(document));


        });


        //Custom JQuery to enable data-text-alt tag on btn class objects
        jQuery(function ($) {
            /*
            $('[data-toggle="collapse"]').on('click', function (e) {
                if ($(this).data('text-alt') != undefined && $(this).data('text-alt') != '') {
                    <?=Javascript::debug('collapse')?>
                    console.log($(this).data('text-alt'));
                    $(this).data('text-original', $(this).html());
                    $(this).html($(this).data('text-alt'));
                    $(this).data('text-alt', $(this).data('text-original'));
                }
            });

*/
            $(document).on('click', '[data-text-alt]', function (e) {
                <?=Javascript::debug('collapse2')?>
                console.log($(this).data('text-alt'));
                $(this).data('text-original', $(this).html());
                $(this).html($(this).data('text-alt'));
                $(this).data('text-alt', $(this).data('text-original'));
            });

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
