<?php
if(isset($_POST["techUserGroup"])){

    $appConfig["userMappings"]["basic"] = $_POST["basicUserGroup"];
    $appConfig["userMappings"]["power"] = $_POST["powerUserGroup"];
    $appConfig["userMappings"]["admin"] = $_POST["adminUserGroup"];
    $appConfig["userMappings"]["tech"] = $_POST["techUserGroup"];
    saveConfig();

}
?>
<div class="mediumSettingsContainer">
        <form action="<?php echo $pageURL."#au_input";?>" method="post">
            <table  class="settingsList">
                <tr>
                    <th style="width:60%">
                        Access Level
                    </th>
                    <th style="width:40%">
                        AD Group Name
                    </th>
                </tr>
                <tr>
                    <td>
                        Basic User Access
                    </td>
                    <td>
                        <input placeholder="Enter Active Directory group name" type="text" name="basicUserGroup" value="<?php echo  $appConfig["userMappings"]["basic"];?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        Power User Access
                    </td>
                    <td>
                        <input placeholder="Enter Active Directory group name" type="text" name="powerUserGroup" value="<?php echo  $appConfig["userMappings"]["power"];?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        Admin User Access
                    </td>
                    <td>
                        <input placeholder="Enter Active Directory group name" type="text" name="adminUserGroup" value="<?php echo  $appConfig["userMappings"]["admin"];?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        Tech User Access
                    </td>
                    <td>
                        <input placeholder="Enter Active Directory group name" type="text" name="techUserGroup" value="<?php echo  $appConfig["userMappings"]["tech"];?>">
                    </td>
                </tr>
                <tr>
                    <td colspan='2'>
                        <?php

                        if(isset($_POST["techUserGroup"])){
                            echo"<div class='alert'>User Group Mappings Updated Succefully!</div>";
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <br/>
            <button id="um_input" type="submit"  value="Update User Group Mappings">Update User Group Mappings</button><br/>

        </form>
    </div>