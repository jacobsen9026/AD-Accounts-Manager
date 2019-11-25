
<div class="shortSettingsContainer">
            <div  class="settingsList">
                <div>
                    <h3>
                        Send Test Email
                    </h3>

                </div>
                <div>
<?php
if(isset($appConfig["emailFromAddress"]) and isset($appConfig["emailFromName"]) and $appConfig["emailFromAddress"]!="" and $appConfig["emailFromName"]!=""){
?>                  
				  <input placeholder="Enter Email Address" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" name="testEmailTo"/>
<?php
				  }
				  else
				  {
					  ?>
					<input placeholder="Please complete From information" type="text" disabled />
					  <?php
				  }
				  ?>
                </div>
                
            </div>
           
    </div>
