<?php
$availableVersion=file_get_contents('https://raw.githubusercontent.com/jacobsen9026/School-Accounts-Manager/master/version.txt');

if(isset($_POST["updateApp"])){	   
    updateApp();
}


if (floatval($availableVersion)>floatval($appConfig["version"])){
?>
<div class="shortSettingsContainer">


    <div  class="settingsList">
        <div>

            <h3>
                Update the Application
            </h3>
            Current Version:<?php echo $appConfig["version"];?><br/>
            Available Version:<?php echo $availableVersion;?><br/><br/>
            <?php

                                                                 if(isset($_POST["updateApp"])){
                                                                     echo"<div class='alert'>Application Updated Succefully!</div>";
                                                                 }
            ?>
        </div>


        <div>

            Update Application<br/><br/><input type="checkbox" name="updateApp" value="updateApp" /><br/><br/>
            <button id="ap_input" type="submit"  value="Update App">Submit</button><br/>


        </div>

    </div>


</div>

<?php
                                                                }
?>