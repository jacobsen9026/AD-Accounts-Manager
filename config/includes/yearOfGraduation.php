<?php

?>
<div class="settingsContainer">
                    <div class="yogList settingsList tableList">
                        <div>
                            <div>
							<h3>
                                Grade
								</h3>
                            </div>
                            <div>
							<h3>
                                Year of Graduation
								</h3>
                            </div>
                        </div>
                        <div>
                            <div>
                                8
                            </div>
                            <div>
                                <input type="text" name="yog8" value="<?php echo $appConfig["gradeMappings"]["8"];?>">
                            </div>
                        </div>
                        <div>
                            <div>
                                7
                            </div>
                            <div>
                                <input type="text" name="yog7" value="<?php echo $appConfig["gradeMappings"]["7"];?>">
                            </div>
                        </div>
                        <div>
                            <div>
                                6
                            </div>
                            <div>
                                <input type="text" name="yog6" value="<?php echo $appConfig["gradeMappings"]["6"];?>">
                            </div>
                        </div>
                        <div>
                            <div>
                                5
                            </div>
                            <div>
                                <input type="text" name="yog5" value="<?php echo $appConfig["gradeMappings"]["5"];?>">
                            </div>
                        </div>
                        <div>
                            <div>
                                4
                            </div>
                            <div>
                                <input type="text" name="yog4" value="<?php echo $appConfig["gradeMappings"]["4"];?>">
                            </div>
                        </div>
                        <div>
                            <div>
                                3
                            </div>
                            <div>
                                <input type="text" name="yog3" value="<?php echo $appConfig["gradeMappings"]["3"];?>">
                            </div>
                        </div>
                        <div>
                            <div>
                                2
                            </div>
                            <div>
                                <input type="text" name="yog2" value="<?php echo $appConfig["gradeMappings"]["2"];?>">
                            </div>
                        </div>
                        <div>
                            <div>
                                1
                            </div>
                            <div>
                                <input type="text" name="yog1" value="<?php echo $appConfig["gradeMappings"]["1"];?>">
                            </div>
                        </div>
                        <div>
                            <div>
                                K
                            </div>
                            <div>
                                <input type="text" name="yogk" value="<?php echo $appConfig["gradeMappings"]["K"];?>">
                            </div>
                        </div>
                        <div>
                            <div>
                                PK4
                            </div>
                            <div>
                                <input type="text" name="yogpk4" value="<?php echo $appConfig["gradeMappings"]["PK4"];?>">
                            </div>
                        </div>
                        <div>
                            <div>
                                PK3
                            </div>
                            <div>
                                <input type="text" name="yogpk3" value="<?php echo $appConfig["gradeMappings"]["PK3"];?>">

                            </div>
                        </div>
                        <?php

                        

                        if(isset($_POST["rolloverGrades"])){
                            echo"<div><div></div><div><div class='alert'>Grades Rolled Over Succefully!</div></div></div>";
                        }

                        ?>
                    </div>
                    
						<br/>
                    <input type="hidden" name="rolloverGrades" value="true"/>
					<a href="<?php echo $pageURL;?>&rolloverGrades=true">
                    <button type="button">
                        Rollover YOG's
                    </button>
					</a>
            </div>