<div class="container">
    <div>
        <h3>
            Profile

        </h3>
    </div>

    <div>
        <select name="theme">
            <?php
            foreach (app\config\Theme::getThemes()as $theme) {
                ?>
                <option value="<?php echo $theme; ?>"><?php echo $theme; ?></option>

                <?php
            }
            ?>

    </div>
</div>
<div class="homepage_footer centered"><?php
    /*
      if ($_SESSION["authenticated_tech"] == "true") {
      //echo "Access Level: Technology";
      echo "<br/>Version: " . $appConfig["configuredVersion"];
      //echo "<br/>Tech: ".$_SESSION["authenticated_tech"];
      //echo "<br/>Admin: ".$_SESSION["authenticated_admin"];
      //echo "<br/>Power: ".$_SESSION["authenticated_power"];
      //echo "<br/>Basic: ".$_SESSION["authenticated_basic"];
      } elseif ($_SESSION["authenticated_admin"] == "true") {
      echo "Access Level: Office";
      } elseif ($_SESSION["authenticated_power"] == "true") {
      echo "Access Level: Tech Teacher";
      } elseif ($_SESSION["authenticated_basic"] == "true") {
      echo "Access Level: Teacher";
      } else {
      echo "Currently Not Logged In";
      }
     *
     */
    ?>
</div>