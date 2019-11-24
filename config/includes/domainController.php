<?php

if(isset($_POST["domainController"])){
    $appConfig["domainController"] = trim($_POST["domainController"]) ;

    saveConfig();
}
?>
<div class="shortSettingsContainer">
        <form action="<?php echo $pageURL."#au_input";?>" method="post">
            <table  class="settingsList">
                <tr>
                    <th>
                        Domain Controller
                    </th>

                </tr>
                <tr>

                    <td>
                        <input placeholder="Enter hostname or domain NetBIOS" type="text" name="domainController" value="<?php echo  $appConfig["domainController"];?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php

                        if(isset($_POST["domainController"])){
                            echo"<div class='alert'>Domain Controller Updated Succefully!</div>";
                        }
                        ?>
                    </td>
                </tr>

            </table>
            <br/>
            <button id="dc_input" type="submit"  value="Update Domain Controller">Update Domain Controller</button><br/>

        </form>
    </div>