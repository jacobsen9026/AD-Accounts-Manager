<div  class="navigationButton">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">WebSiteName</a>
        </div>
        <ul class="nav navbar-nav">

            <?php
            //if(isset($_SESSION['authenticated_basic'])){
            //if($_SESSION['authenticated_basic']=="true"){
            ?>
            <li class="active">

        <!--<img src="./img/user_avatar.png"/>-->
                Test User<?php // echo $_SESSION["username"];          ?>
            </li>

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
        </ul>
    </div>
</div>
<?php
// }
//}
?>

</div>
