<?php
//Start the PHP session
session_start();

//Load all major functions of the webapp
include($_SERVER['DOCUMENT_ROOT']."/includes/backendIncludes.php");

//Intialize Goto variable and check if the Get goto variable is set, if so set it as the Goto variable
global $goto;
$goto='';
if(isset($_GET)){
    if(isset($_GET["goto"])){
        $goto=$_GET["goto"];
    }
}

//Get the requested URL
if(isset($_SERVER)){
    $pageURL= $_SERVER['REQUEST_URI'];
}




//Session Timeout based on setting in config
if(isset($_SESSION)){
    if(isset($_SESSION['timeout'])){
        if(($_SESSION['timeout']+intval($appConfig["sessionTimeout"])<time()) and ($_SESSION["authenticated_basic"]=="true")){
            session_unset();
            $timedOut="true";
        }
    }else{
        session_unset();
    }
}

//Old sandbox variable which can be deleted probably
//$sandbox=false;

//Detect security level and write it to $protocol variable
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    // this is HTTPS
    $protocol  = "https";
} else {
    // this is HTTP
    $protocol  = "http";
}

//Redirect to FQDN if using hostname
if(isset($appConfig["websiteFQDN"]) && $appConfig["websiteFQDN"]!=""){
    if(strtolower($_SERVER['SERVER_NAME'])!=strtolower($appConfig["websiteFQDN"])){
        if(isset($appConfig["redirectHTTP"])){
            if($appConfig["redirectHTTP"]){
                header("location: https://".$appConfig["websiteFQDN"].$pageURL);
            }else{
                header("location: ".$protocol."://".$appConfig["websiteFQDN"].$pageURL);
            }
        }else{
            header("location: ".$protocol."://".$appConfig["websiteFQDN"].$pageURL);
        }
    }
}else{
    if(strtolower($_SERVER['SERVER_NAME'])==strtolower($_SERVER['COMPUTERNAME'])){
        if(isset($appConfig["redirectHTTP"])){
            if($appConfig["redirectHTTP"]){

                header("location: https://".$appConfig["websiteFQDN"].$pageURL);
            }
        }
    }
}


//Set theme if posted
if(isset($_POST["theme"])){
    if($_POST["theme"]=="light"){
        setcookie("theme", "light", time() + (86400 * 3650)); // 86400 = 1 day	
        header('Location:'.$pageURL);

    }	
    elseif ($_POST["theme"]=="dark"){
        setcookie("theme", "dark", time() + (86400 * 3650)); // 86400 = 1 day	
        header('Location:'.$pageURL);
    }


}

//Check to make sure user is logged in
if(!isset($_SESSION['authenticated_basic'])){
    $_SESSION['authenticated_basic']=false;
}

?>
<html>
    <head>

        <title>		
            <?php
            echo $appConfig["webAppName"];
            ?>
        </title>

        <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json">
        <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="theme-color" content="#ffffff">


        <?php

        //Include Stylesheets
        include("./includes/stylesheets.php");

        ?>

        <script src="/scripts/navigationScripts.js"></script>
        <script src="/scripts/otherScripts.js"></script>
        <script src="/scripts/auth.js"></script>

        <script src="/scripts/js-toast-master/toast.js"></script>
        <script src="/lib/jquery.min.js"></script>
        <script src="/lib/jquery.plugin.js"></script>






        <script type="text/javascript">
            var timeoutTimer;
            function startSessionTimeoutTimer(){
                timer=setTimeout(function(){showSessionTimeoutWarningMessege(); }, <?php echo (($appConfig["sessionTimeout"]*1000)-(($appConfig["sessionTimeout"]*1000)*.2));?>);
            }
            function showSessionTimeoutWarningMessege(){
                blurPage();
                document.getElementById("sessionTimeoutWarningContainer").style="visibility:visible";
                timeoutTimer=setTimeout(function(){showSessionTimedOutMessege(); },<?php echo (($appConfig["sessionTimeout"]*1000)*.2);?>);
            }
        </script>




    </head>


    <body <?php if (isset($_SESSION["authenticated_basic"]) && $_SESSION["authenticated_basic"]=="true"){ ?> onload="startSessionTimeoutTimer();"<?php } ?> >




        <?php
        if($appConfig["installComplete"]){
            //Load the top menu navigation
            include("./includes/navigation.php");
        }
        ?>


        <?php
        //Load waiting animation that consumes the screen during operations and debug console.
        include("./includes/pageLoader.php");
        include("./includes/debugConsole.php");
        include("./includes/debugConfig.php");



        if($_SESSION["authenticated_basic"]=="true"){
            include("./includes/sessionTimeoutWarning.php");
        }


        ?>
        <div id="wrapper" class=''>



