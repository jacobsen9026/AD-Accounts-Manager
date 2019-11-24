<?php

if(isset($_POST["websiteFQDN"])){
    $websiteFQDN=$_POST["websiteFQDN"];
    if(strpos( $websiteFQDN,"//")!=false){
        $websiteFQDN=substr( $websiteFQDN,strpos( $websiteFQDN,"//")+2,strlen( $websiteFQDN)-strpos( $websiteFQDN,"//")-2);
        }
    $appConfig["websiteFQDN"] = trim($websiteFQDN) ;

    saveConfig();
}
?>
<div class="shortSettingsContainer">
        <form action="<?php echo $pageURL."#au_input";?>" method="post">
            <table  class="settingsList">
                <tr>
                    <th>
                        Website FQDN
                    </th>

                </tr>
                <tr>

                    <td>
                        <input  placeholder="Enter FQDN" type="text" name="websiteFQDN" value="<?php echo  $appConfig["websiteFQDN"];?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php

                        if(isset($_POST["websiteFQDN"])){
                            echo"<div class='alert'>Website FQDN Updated Succefully!</div>";
                        }
                        ?>
                    </td>
                </tr>

            </table>
            <br/>
            <button id="dc_input" type="submit"  value="Update Website FQDN">Update Website FQDN</button><br/>

        </form>
    </div>