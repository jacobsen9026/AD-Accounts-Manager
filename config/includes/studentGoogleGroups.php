<?php
if(isset($_POST["sgg8"])){

    $appConfig["studentEmailGroups"]["8"]=$_POST["sgg8"];
    $appConfig["studentEmailGroups"]["7"]=$_POST["sgg7"];
    $appConfig["studentEmailGroups"]["6"]=$_POST["sgg6"];
    $appConfig["studentEmailGroups"]["5"]=$_POST["sgg5"];
    $appConfig["studentEmailGroups"]["4"]=$_POST["sgg4"];
    $appConfig["studentEmailGroups"]["3"]=$_POST["sgg3"];
    $appConfig["studentEmailGroups"]["2"]=$_POST["sgg2"];
    $appConfig["studentEmailGroups"]["1"]=$_POST["sgg1"];
    $appConfig["studentEmailGroups"]["K"]=$_POST["sggk"];
    $appConfig["studentEmailGroups"]["PK4"]=$_POST["sggpk4"];
    $appConfig["studentEmailGroups"]["PK3"]=$_POST["sggpk3"];

    saveConfig();
}

?>
<div class="settingsContainer">
                <form action="<?php echo $pageURL."#sgg_input";?>" method="post">
                    <table  class="settingsList">
                        <tr>
                            <th>
                                Grade
                            </th>
                            <th>
                                Student Google Group
                            </th>
                        </tr>
                        <tr>
                            <td>
                                8
                            </td>
                            <td>
                                <input  placeholder="Enter 8th grade student email group" type="text" name="sgg8" value="<?php echo $appConfig["studentEmailGroups"]["8"];?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                7
                            </td>
                            <td>
                                <input  placeholder="Enter 7th grade student email group" type="text" name="sgg7" value="<?php echo $appConfig["studentEmailGroups"]["7"];?>"/>

                        </tr>
                    <tr>
                        <td>
                            6
                        </td>
                        <td>
                            <input  placeholder="Enter 6th grade student email group" type="text" name="sgg6" value="<?php echo $appConfig["studentEmailGroups"]["6"];?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            5
                        </td>
                        <td>
                            <input  placeholder="Enter 5th grade student email group" type="text" name="sgg5" value="<?php echo $appConfig["studentEmailGroups"]["5"];?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            4
                        </td>
                        <td>
                            <input  placeholder="Enter 4th grade student email group" type="text" name="sgg4" value="<?php echo $appConfig["studentEmailGroups"]["4"];?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            3
                        </td>
                        <td>
                            <input  placeholder="Enter 3rd grade student email group" type="text" name="sgg3" value="<?php echo $appConfig["studentEmailGroups"]["3"];?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            2
                        </td>
                        <td>
                            <input  placeholder="Enter 2nd grade student email group" type="text" name="sgg2" value="<?php echo $appConfig["studentEmailGroups"]["2"];?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            1
                        </td>
                        <td>
                            <input  placeholder="Enter 1st grade student email group" type="text" name="sgg1" value="<?php echo $appConfig["studentEmailGroups"]["1"];?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            K
                        </td>
                        <td>
                            <input  placeholder="Enter kindergarten grade student email group" type="text" name="sggk" value="<?php echo $appConfig["studentEmailGroups"]["K"];?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            PK4
                        </td>
                        <td>
                            <input  placeholder="Enter 4 Yr PreK student email group" type="text" name="sggpk4" value="<?php echo $appConfig["studentEmailGroups"]["PK4"];?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            PK3
                        </td>
                        <td>
                            <input  placeholder="Enter 3 Yr PreK student email group" type="text" name="sggpk3" value="<?php echo $appConfig["studentEmailGroups"]["PK3"];?>"/>
                        </td>
                    </tr>
                    <?php

                    if(isset($_POST["sgg8"])){
                        echo"<tr><td></td><td><div class='alert'>Group Mappings Updated Succefully!</div></td></tr>";
                    }
                    ?>
                    </table>
                <br/>
                <button id="gm_input" type="submit"  value="Update Grade Mappings">Update Grade Mappings</button><br/>

                </form>
    </div>