<?php
if(isset($_POST["emailFromName"])){
    $appConfig["emailFromName"] = trim($_POST["emailFromName"]) ;

    saveConfig();
}
?>
<div class="shortSettingsContainer">
        <form action="<?php echo $pageURL."#ef_input";?>" method="post">
            <table  class="settingsList">
                <tr>
                    <th>
                        Email From Name (eg:Account Manager)
                    </th>

                </tr>
                <tr>

                    <td>
                        <input type="text" name="emailFromName" value="<?php echo  $appConfig["emailFromName"];?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php

                        if(isset($_POST["emailFromName"])){
                            echo"<div class='alert'>Email From Name Updated Succefully!</div>";
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <br/>
            <button id="ef_input" type="submit"  value="Update Email From Name">Update From Name</button><br/>

        </form>
    </div>