<?php
if(isset($_POST["domainNetBIOS"])){
    $appConfig["domainNetBIOS"] = trim($_POST["domainNetBIOS"]) ;

    saveConfig();
}
?>
<div class="shortSettingsContainer">
        <form action="/?goto=/config/index.php#au_input" method="post">
            <table  class="settingsList">
                <tr>
                    <th>
                        Domain NetBIOS Name
                    </th>

                </tr>
                <tr>

                    <td>
                        <input placeholder="Enter domain NetBIOS name" type="text" name="domainNetBIOS" value="<?php echo  $appConfig["domainNetBIOS"];?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php

                        if(isset($_POST["domainNetBIOS"])){
                            echo"<div class='alert'>Domain NetBIOS Name Updated Succefully!</div>";
                        }
                        ?>
                    </td>
                </tr>

            </table>
            <br/>
            <button id="dnb_input" type="submit"  value="Update Domain NetBios">Update Domain NetBios</button><br/>

        </form>
    </div>