<?php
/*
  //Start the PHP session
  session_start();

  //Load all major functions of the webapp
  include("./app/includes/backendIncludes.php");

  //Intialize Goto variable and check if the Get goto variable is set, if so set it as the Goto variable

  if (isset($_GET)) {
  global $goto;
  $goto = '';
  if (isset($_GET["goto"])) {
  $goto = $_GET["goto"];
  }
  }

  //Intialize Goto variable and check if the Get goto variable is set, if so set it as the Goto variable

  if (isset($_GET)) {
  if (isset($_GET["grab"])) {
  global $grab;
  $grab = '';
  $grab = $_GET["grab"];
  }
  }
  //Intialize download variable and check if the download goto variable is set, if so set it as the download variable

  if (isset($_GET)) {
  global $download;
  $download = '';
  if (isset($_GET["download"]) and $_SESSION["authenticated_tech"] == "true") {
  $filepath = "." . $_GET["download"];
  if ($filepath == "./") {
  //echo "starting";
  $filepath1 = "/temp/" . $appConfig["webAppName"] . "v" . str_replace(".", "-", $appConfig["version"]) . "-backup.zip";
  generateAppBackup($filepath1);
  $filepath = "." . $filepath1;
  //echo "back";

  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Length: ' . filesize($filepath));
  flush(); // Flush system output buffer
  readfile($filepath);
  deleteTempFolder();

  exit;
  }
  if (file_exists($filepath)) {
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Length: ' . filesize($filepath));
  flush(); // Flush system output buffer
  readfile($filepath);
  exit;
  }
  }
  }

  //Get the requested URL
  if (isset($_SERVER)) {
  $pageURL = $_SERVER['REQUEST_URI'];
  }




  //Session Timeout based on setting in config
  if (isset($_SESSION)) {
  if (isset($_SESSION['timeout'])) {
  if (($_SESSION['timeout'] + intval($appConfig["sessionTimeout"]) < time()) and ($_SESSION["authenticated_basic"] == "true")) {
  session_unset();
  $timedOut = "true";
  }
  } else {
  session_unset();
  }
  }

  //Old sandbox variable which can be deleted probably
  //$sandbox=false;
  //Detect security level and write it to $protocol variable
  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
  // this is HTTPS
  $protocol = "https";
  } else {
  // this is HTTP
  $protocol = "http";
  }

  //Redirect to FQDN if using hostname
  if (isset($appConfig["websiteFQDN"]) && $appConfig["websiteFQDN"] != "") {
  if (strtolower($_SERVER['SERVER_NAME']) != strtolower($appConfig["websiteFQDN"])) {
  if (isset($appConfig["redirectHTTP"])) {
  if ($appConfig["redirectHTTP"]) {
  header("location: https://" . $appConfig["websiteFQDN"] . $pageURL);
  } else {
  header("location: " . $protocol . "://" . $appConfig["websiteFQDN"] . $pageURL);
  }
  } else {
  header("location: " . $protocol . "://" . $appConfig["websiteFQDN"] . $pageURL);
  }
  }
  } else {
  if (strtolower($_SERVER['SERVER_NAME']) == strtolower($_SERVER['COMPUTERNAME'])) {
  if (isset($appConfig["redirectHTTP"])) {
  if ($appConfig["redirectHTTP"]) {

  header("location: https://" . $appConfig["websiteFQDN"] . $pageURL);
  }
  }
  }
  }


  //Set theme if posted
  if (isset($_POST["theme"])) {
  if ($_POST["theme"] == "light") {
  setcookie("theme", "light", time() + (86400 * 3650)); // 86400 = 1 day
  header('Location:' . $pageURL);
  } elseif ($_POST["theme"] == "dark") {
  setcookie("theme", "dark", time() + (86400 * 3650)); // 86400 = 1 day
  header('Location:' . $pageURL);
  }
  }

  //Check to make sure user is logged in
  if (!isset($_SESSION['authenticated_basic'])) {
  $_SESSION['authenticated_basic'] = false;
  }
 *
 */
?>
<!DOCTYPE html>

<html lang="en">
    <head>

        <title>
            <?php
            //echo $appConfig["webAppName"];
            ?>
        </title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

        <link rel="stylesheet" href="/css/style2.css">

        <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json">
        <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="theme-color" content="#ffffff">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <?php
        /*
          if (isset($_COOKIE["theme"])) {
          if ($_COOKIE["theme"] == "light") {
          echo '<link rel="stylesheet" type="text/css" href="/style/lightTheme.css">';
          } elseif ($_COOKIE["theme"] == "dark") {

          echo '<link rel="stylesheet" type="text/css" href="/style/darkTheme.css">';
          }
          } else {
          echo '<link rel="stylesheet" type="text/css" href="/style/lightTheme.css">';
          }
          if (isset($_SESSION["authenticated_tech"]) and $appConfig["debugMode"] and $_SESSION["authenticated_tech"]) {
          echo '<link rel="stylesheet" type="text/css" href="/style/sandbox.css">';
          }
         * */
        ?>




        <!--
                <script src="/scripts/navigationScripts.js"></script>
                <script src="/scripts/otherScripts.js"></script>
                <script src="/scripts/auth.js"></script>

                <script src="/scripts/lib/js-toast-master/toast.js"></script>
                <script src="/scripts/lib/jquery.min.js"></script>
                <script src="/scripts/lib/jquery.plugin.js"></script>


        -->



        <script type="text/javascript">
            var timeoutTimer;
            function startSessionTimeoutTimer() {
                timer = setTimeout(function () {
                    showSessionTimeoutWarningMessege();
                }, <?php //echo (($appConfig["sessionTimeout"] * 1000) - (($appConfig["sessionTimeout"] * 1000) * .2));                   ?>);
            }
            function showSessionTimeoutWarningMessege() {
                blurPage();
                document.getElementById("sessionTimeoutWarningContainer").style = "visibility:visible";
                timeoutTimer = setTimeout(function () {
                    showSessionTimedOutMessege();
                },<?php //echo (($appConfig["sessionTimeout"] * 1000) * .2);                   ?>);
            }
        </script>




    </head>


    <body>
        <?php
        /*
          if ($_SESSION["authenticated_basic"] == "true" and!isset($grab)) {
          //Load waiting animation that consumes the screen during operations and debug console.
          include("./app/includes/pageLoader.php");
          include("./app/includes/sessionTimeoutWarning.php");
          //echo "./app/views".$grab;
          }



          if (isset($_SESSION['authenticated_tech']) and!isset($grab)) {
          if ($_SESSION["authenticated_tech"] == "true") {
          if ($appConfig["debugMode"]) {
          include("./app/includes/debugConsole.php");
          include("./app/includes/debugConfig.php");
          include("./app/includes/debugConsole.php");
          include("./app/includes/debugInclude.php");
          ?>

          <div class="debugFloatingToolsContainer">
          <div title="Debug Mode is On" onmouseover="hoverOverEditButton(this);" onmouseleave="revertEditButton(this);" onclick="window.open('/?goto=/config/index.php#dm_input');" class="floatingButton">
          <img src="/img/warning2.png"/>
          </div>
          <div title="Open Debug Console" onmouseover="hoverOverEditButton(this);" onmouseleave="revertEditButton(this);" onclick='document.getElementById("debugConsoleContainer").style = "visibility:visible";' class="floatingButton">
          <img src="/img/console.png"/>
          </div>
          <div title="Open Config Debug" onmouseover="hoverOverEditButton(this);" onmouseleave="revertEditButton(this);" onclick='document.getElementById("debugConfigContainer").style = "visibility:visible";' class="floatingButton">
          <img src="/img/config.png"/>
          </div>
          <div title="Open Config Includes" onmouseover="hoverOverEditButton(this);" onmouseleave="revertEditButton(this);" onclick='document.getElementById("debugIncludeContainer").style = "visibility:visible";' class="floatingButton">
          <img src="/img/console.png"/>
          </div>
          </div>
          <?php
          }
          }
          }
         *
         */
        ?>


        <?php
        /*
          if ($appConfig["installComplete"] and!isset($grab)) {
          //Load the top menu navigation
          include("./app/includes/navigation.php");
          }
         *
         */
        ?>



        <div class='container-fluid p-0'>



