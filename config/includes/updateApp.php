<?php

if(isset($_POST["updateApp"])){
    
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
                        Current Version:<?php echo $appConfig["version"];?><br/>
						Available Version:
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php

                        if(isset($_POST["updateApp"])){
                            echo"<div class='alert'>Application Updated Succefully!</div>";
                        }
                        ?>
                    </td>
                </tr>

            </table>
            <br/>
            <button id="ap_input" type="submit"  value="Update Admin Password">Update App to Latest Version</button><br/>

        </form>
    </div>