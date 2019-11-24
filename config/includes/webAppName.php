<?php
if(isset($_POST["webAppName"])){
    $appConfig["webAppName"] = trim($_POST["webAppName"]) ;

    saveConfig();
}
?>
<div class="shortSettingsContainer">
        <form action="<?php echo $pageURL."#au_input";?>" method="post">
            <table  class="settingsList">
                <tr>
                    <th>
                        Web App Name
                    </th>

                </tr>
                <tr>

                    <td>
                        <input placeholder="Enter Name" type="text" name="webAppName" value="<?php echo  $appConfig["webAppName"];?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php

                        if(isset($_POST["webAppName"])){
                            echo"<div class='alert'>Web App Name Updated Succefully!</div>";
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <br/>
            <button id="dm_input" type="submit"  value="Update Web App Name">Update Web App Name</button><br/>

        </form>
    </div>