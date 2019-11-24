<?php
if(isset($_POST["emailFromAddress"])){
    $appConfig["emailFromAddress"] = trim($_POST["emailFromAddress"]) ;

    saveConfig();
}
?>
<div class="shortSettingsContainer">
        <form action="<?php echo $pageURL."#ef_input";?>" method="post">
            <table  class="settingsList">
                <tr>
                    <th>
                        Email From Address (with FQDN)
                    </th>

                </tr>
                <tr>

                    <td>
                        <input type="text" name="emailFromAddress" value="<?php echo  $appConfig["emailFromAddress"];?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php

                        if(isset($_POST["emailFromAddress"])){
                            echo"<div class='alert'>Email From Address Updated Succefully!</div>";
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <br/>
            <button id="ef_input" type="submit"  value="Update Email From Address">Update From Address</button><br/>

        </form>
    </div>