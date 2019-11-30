<div  class="navigationButton">
    <?php //if(isset($_SESSION['authenticated_basic'])){
    //if($_SESSION['authenticated_basic']=="true"){
    ?>
    <button id="userButton" onclick="showUserDropdown()">

        <img src="./img/user_avatar.png"/>
        <div id="loggedInUser">User<?php //echo $_SESSION["username"];?></div>
    </button>

    <div id="userDropdown" class="subnavigation" >
		
        <?php
		$this->renderPartial('/common/navigation/user/homeButton');
        if(strpos($_SERVER['REQUEST_URI'],"/?")!== false){
           // include("./app/views/navigation/user/homeButton.php");
        }

/*
        include("./app/views/navigation/user/themeButton.php");
        include("./app/views/navigation/user/settingsButton.php");
		
        include("./app/views/navigation/user/helpButton.php");
        include("./app/views/navigation/user/helpdeskButton.php");
        include("./app/views/navigation/user/logoutButton.php");
*/
        ?>
        <span class="spacer"></span>
    </div>	
    <?php
   // }
//}
    ?>

</div>
