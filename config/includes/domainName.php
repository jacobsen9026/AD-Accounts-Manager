<?php
if(isset($_POST["domainName"])){
    $appConfig["domainName"] = trim($_POST["domainName"]) ;

    saveConfig();
}
?>
<div class="shortSettingsContainer">
        <form action="<?php echo $pageURL."#au_input";?>" method="post">
            <table  class="settingsList">
                <tr>
                    <th>
                        Domain Name
                    </th>

                </tr>
                <tr>

                    <td>
                        <input placeholder="Enter FQDN" type="text" name="domainName" value="<?php echo  $appConfig["domainName"];?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php

                        if(isset($_POST["domainName"])){
                            echo"<div class='alert'>Domain Name Updated Succefully!</div>";
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <br/>
            <button id="dm_input" type="submit"  value="Update Domain Name">Update Domain Name</button><br/>

        </form>
    </div>