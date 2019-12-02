
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php
        echo $this->renderPartial('/common/headers/meta');
        echo $this->renderPartial('/common/headers/css');
        echo $this->renderPartial('/common/headers/js');
        //$this->renderSection('/partials/meta');
        ?>
    </head>
    <body <?php //if (isset($_SESSION["authenticated_basic"]) && $_SESSION["authenticated_basic"]=="true"){  ?> onload="startSessionTimeoutTimer();"<?php //}  ?> >
        <div id="wrapper" class=''>
            <div id="container" class="container">
                <div id="loginPopupContainer">
                    <?php
                    //echo $this->renderPartial('/common/navigation');
                    $this->renderSection('content');
                    ?>

                    <?php
                    //if($appConfig["installComplete"] and !isset($grab)){
                    //Load the top menu navigation
                    //    include("./app/includes/navigation.php");
                    //}
                    ?>

                </div>
            </div>
        </div>
    </body>
</html>



