<td  class="navigationButton">
    <?php if(isset($_SESSION['authenticated_basic'])){
    if($_SESSION['authenticated_basic']=="true"){
    ?>
    <button id="userButton" onclick="showUserDropdown()">

        <img src="./img/user_avatar.png"/>
        <div id="loggedInUser"><?php echo $_SESSION["username"];?></div>
    </button>

    <div id="userDropdown" class="subnavigation" >

        <?php

        if(strpos($_SERVER['REQUEST_URI'],"/?")!== false){
            include("./includes/navigation/user/homeButton.php");
        }


        include("./includes/navigation/user/themeButton.php");
        include("./includes/navigation/user/settingsButton.php");
        if (isset($_SESSION['authenticated_tech'])){
            if($_SESSION["authenticated_tech"]=="true"){
        ?>
        <div id="siteEditorButtonsContainer">
            <?php
                include("./includes/navigation/user/editorButton.php");
                include("./includes/navigation/user/editButtons.php");
            ?>
        </div>
        <?php
            }
        }
        include("./includes/navigation/user/helpButton.php");
        include("./includes/navigation/user/helpdeskButton.php");
        include("./includes/navigation/user/logoutButton.php");

        ?>
        <span class="spacer"></span>
    </div>	
    <?php
    }
}
    ?>

</td>
