
<div class="shortSettingsContainer">
            <div  class="settingsList">
                <div>
                    <h3>
                        Debug Mode
                    </h3>
				</div>
                

                    <div>
					<?php //echo  $appConfig["debugMode"];
                        //echo $_POST["debugModeCheck"];?>
                        <input type="text" name="debugMode" hidden/>
                        <input type="checkbox"  name="debugModeCheck" value="true" <?php //echo  $appConfig["debugMode"];
                                                                                            if(isset($appConfig["debugMode"])){
                                                                                                if($appConfig["debugMode"]){
                                                                                                    echo "checked";
                                                                                                }
                                                                                                }
                                                                                            ?>>
                    </div>
                </div>
                

            
            
    </div>