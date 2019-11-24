<?php

if(isset($_POST["adminPassword"])){
    $appConfig["adminPassword"] =  hash('sha256',trim($_POST["adminPassword"])) ;

    saveConfig();
}
?>
 <div class="shortSettingsContainer">
        <form action="<?php echo $pageURL."#ap_input";?>" method="post">
            <table  class="settingsList">
                <tr>
                    <th>
                        Set New Admin Password
                    </th>

                </tr>
                <tr>

                    <td>
                        <input placeholder="Enter new password" type="password" name="adminPassword" value="">
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php

                        if(isset($_POST["adminPassword"])){
                            echo"<div class='alert'>Admin Password Updated Succefully!</div>";
                        }
                        ?>
                    </td>
                </tr>

            </table>
            <br/>
            <button id="ap_input" type="submit"  value="Update Admin Password">Update Admin Password</button><br/>

        </form>
    </div>