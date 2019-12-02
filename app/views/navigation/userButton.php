<td  class="navigationButton">
    <?php
    //if(isset($_SESSION['authenticated_basic'])){
    //if($_SESSION['authenticated_basic']=="true"){
    ?>
    <button id="userButton" onclick="showUserDropdown()">

        <!--<img src="./img/user_avatar.png"/>-->
        <div id="loggedInUser">Test User<?php // echo $_SESSION["username"];     ?></div>
    </button>

    <div id="userDropdown" class="subnavigation" >

        <?php
        /*
          if (strpos($_SERVER['REQUEST_URI'], "/?") !== false) {
          include("./app/views/navigation/user/homeButton.php");
          }


          include("./app/views/navigation/user/themeButton.php");
          include("./app/views/navigation/user/settingsButton.php");
          /*
          if (isset($_SESSION['authenticated_tech'])){
          if($_SESSION["authenticated_tech"]=="true"){
          ?>
          <div id="siteEditorButtonsContainer">
          <?php
          include("./app/views/navigation/user/editorButton.php");
          include("./app/views/navigation/user/editButtons.php");
          ?>
          </div>
          <?php
          }
          }
         */
        /*
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

</td>
