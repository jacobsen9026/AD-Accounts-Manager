<?php
if(isset($_POST["yog8"])){
    //global $appConfig;
    $appConfig["gradeMappings"]["8"]=$_POST["yog8"];
    $appConfig["gradeMappings"]["7"]=$_POST["yog7"];
    $appConfig["gradeMappings"]["6"]=$_POST["yog6"];
    $appConfig["gradeMappings"]["5"]=$_POST["yog5"];
    $appConfig["gradeMappings"]["4"]=$_POST["yog4"];
    $appConfig["gradeMappings"]["3"]=$_POST["yog3"];
    $appConfig["gradeMappings"]["2"]=$_POST["yog2"];
    $appConfig["gradeMappings"]["1"]=$_POST["yog1"];
    $appConfig["gradeMappings"]["K"]=$_POST["yogk"];
    $appConfig["gradeMappings"]["PK4"]=$_POST["yogpk4"];
    $appConfig["gradeMappings"]["PK3"]=$_POST["yogpk3"];
    saveConfig();
}

if(isset($_POST["rolloverGrades"])){

    $appConfig["gradeMappings"]["8"]=(intval($appConfig["gradeMappings"]["8"])+intval(1));
    $appConfig["gradeMappings"]["7"]=(intval($appConfig["gradeMappings"]["7"])+intval(1));
    $appConfig["gradeMappings"]["6"]=(intval($appConfig["gradeMappings"]["6"])+intval(1));
    $appConfig["gradeMappings"]["5"]=(intval($appConfig["gradeMappings"]["5"])+intval(1));
    $appConfig["gradeMappings"]["4"]=(intval($appConfig["gradeMappings"]["4"])+intval(1));
    $appConfig["gradeMappings"]["3"]=(intval($appConfig["gradeMappings"]["3"])+intval(1));
    $appConfig["gradeMappings"]["2"]=(intval($appConfig["gradeMappings"]["2"])+intval(1));
    $appConfig["gradeMappings"]["1"]=(intval($appConfig["gradeMappings"]["1"])+intval(1));
    $appConfig["gradeMappings"]["K"]=(intval($appConfig["gradeMappings"]["K"])+intval(1));
    $appConfig["gradeMappings"]["PK4"]=(intval($appConfig["gradeMappings"]["PK4"])+intval(1));
    $appConfig["gradeMappings"]["PK3"]=(intval($appConfig["gradeMappings"]["PK3"])+intval(1));
    //print_r($appConfig["gradeMappings"]);
    //var_export $appConfig["gradeMappings"];
    saveConfig();



}
?>
<div class="settingsContainer">
                <form action="<?php echo $pageURL."#pe_input";?>" method="post">
                    <table class="yogList settingsList ">
                        <tr>
                            <th>
                                Grade
                            </th>
                            <th>
                                Year of Graduation
                            </th>
                        </tr>
                        <tr>
                            <td>
                                8
                            </td>
                            <td>
                                <input type="text" name="yog8" value="<?php echo $appConfig["gradeMappings"]["8"];?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                7
                            </td>
                            <td>
                                <input type="text" name="yog7" value="<?php echo $appConfig["gradeMappings"]["7"];?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                6
                            </td>
                            <td>
                                <input type="text" name="yog6" value="<?php echo $appConfig["gradeMappings"]["6"];?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                5
                            </td>
                            <td>
                                <input type="text" name="yog5" value="<?php echo $appConfig["gradeMappings"]["5"];?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                4
                            </td>
                            <td>
                                <input type="text" name="yog4" value="<?php echo $appConfig["gradeMappings"]["4"];?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                3
                            </td>
                            <td>
                                <input type="text" name="yog3" value="<?php echo $appConfig["gradeMappings"]["3"];?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                2
                            </td>
                            <td>
                                <input type="text" name="yog2" value="<?php echo $appConfig["gradeMappings"]["2"];?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                1
                            </td>
                            <td>
                                <input type="text" name="yog1" value="<?php echo $appConfig["gradeMappings"]["1"];?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                K
                            </td>
                            <td>
                                <input type="text" name="yogk" value="<?php echo $appConfig["gradeMappings"]["K"];?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                PK4
                            </td>
                            <td>
                                <input type="text" name="yogpk4" value="<?php echo $appConfig["gradeMappings"]["PK4"];?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                PK3
                            </td>
                            <td>
                                <input type="text" name="yogpk3" value="<?php echo $appConfig["gradeMappings"]["PK3"];?>">

                            </td>
                        </tr>
                        <?php

                        if(isset($_POST["yog8"])){
                            echo"<tr><td></td><td><div class='alert'>Group Mappings Updated Succefully!</div></td></tr>";
                        }

                        if(isset($_POST["rolloverGrades"])){
                            echo"<tr><td></td><td><div class='alert'>Grades Rolled Over Succefully!</div></td></tr>";
                        }

                        ?>
                    </table>
                    <br/>
                    <button id="pe_input" type="submit"  value="Update Grade Mappings">Update Grade Mappings</button><br/>

                </form>

                <form method="post" action="/?goto=/config/index.php">
                    <input type="hidden" name="rolloverGrades" value="true"/>
                    <button type="submit">
                        Rollover YOG's
                    </button>
                </form>
            </div>