<?php
if(isset($_POST["sessionTimeout"])){
    $appConfig["sessionTimeout"] = trim($_POST["sessionTimeout"]) ;

    saveConfig();
}
?>
<div class="shortSettingsContainer">
        <form action="<?php echo $pageURL."#st_input";?>" method="post">
            <table  class="settingsList">
                <tr>
                    <th>
                        Session Timeout
                    </th>

                </tr>
                <tr>

                    <td>
                        <input  placeholder="Time in seconds (eg:1200)" type="text" style="width:25%" name="sessionTimeout" value="<?php echo  $appConfig["sessionTimeout"];?>"> Seconds
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php

                        if(isset($_POST["sessionTimeout"])){
                            echo"<div class='alert'>Session Timeout Updated Succefully!</div>";
                        }
                        ?>
                    </td>
                </tr>

            </table>
            <br/>
            <button id="st_input" type="submit"  value="Update Session Timeout">Update Session Timeout</button><br/>

        </form>
    </div>