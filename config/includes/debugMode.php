<?php
debug("Debug Mode is on");
if(isset($_POST["debugMode"])){
    print_r ($_POST["debugMode"]);
    if($_POST['debugModeCheck']==true){
    $appConfig["debugMode"]=true;
    }else{
    $appConfig["debugMode"]=false;
    }
    //$appConfig["debugMode"] = IsChecked($_POST["debugMode"]) ;

    saveConfig();
}
?>
<div class="shortSettingsContainer">
        <form action="<?php echo $pageURL."#rh_input";?>" method="post">
            <table  class="settingsList">
                <tr>
                    <th>
                        Debug Mode
                    </th>

                </tr>
                <tr>

                    <td><?php //echo  $appConfig["debugMode"];
                        //echo $_POST["debugModeCheck"];?>
                        <input type="text" name="debugMode" hidden/>
                        <input type="checkbox"  name="debugModeCheck" value="true" <?php //echo  $appConfig["debugMode"];
                                                                                            if(isset($appConfig["debugMode"])){
                                                                                                if($appConfig["debugMode"]){
                                                                                                    echo "checked";
                                                                                                }
                                                                                                }
                                                                                            ?>>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php

                        if(isset($_POST["debugMode"])){
                            echo"<div class='alert'>Debug Mode Updated Succefully!</div>";
                        }
                        ?>
                    </td>
                </tr>

            </table>
            <br/>
            <button id="dm_input" type="submit"  value="Update Debug Mode">Update Debug Mode</button><br/>

        </form>
    </div>