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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <link rel="stylesheet" href="/css/style2.css">

    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">


    <?php
    /**
     * Handle Themes
     */
    if ($this->app->user->theme != \app\config\Theme::DEFAULT_THEME and $this->app->user->theme != \app\config\Theme::BLUE_THEME) {
        system\app\AppLogger::get()->debug("theme is " . $this->app->user->theme);
        $theme = explode("_", $this->app->user->theme);
        $theme = $theme[0] . ucfirst($theme[1]);

        echo ' <link rel="stylesheet" href="/css/' . $theme . '.css">';
    }
    /**
      if ($this->app->user->theme == \app\config\Theme::RED_THEME) {
      system\app\AppLogger::get()->debug("theme is red");
      echo ' <link rel="stylesheet" href="/css/redTheme.css">';
      }
      if ($this->app->user->theme == \app\config\Theme::GREEN_THEME) {
      system\app\AppLogger::get()->debug("theme is green");
      echo ' <link rel="stylesheet" href="/css/greenTheme.css">';
      }
     *
     */
    ?>

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
