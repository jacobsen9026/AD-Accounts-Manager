
<div class="mediumSettingsContainer">
            <div  class="settingsList tableList">
                <div>
				<div>
                    <h3>
                       Access Level
                    </h3>
					</div>
					<div>
                    <h3>
                        AD Group Name
                    </h3>
					</div>
                </div>
                <div>
                    <div>
                        Basic User Access
                    </div>
                    <div>
                        <input placeholder="Enter Active Directory group name" type="text" name="basicUserGroup" value="<?php echo  $appConfig["userMappings"]["basic"];?>">
                    </div>
                </div>
                <div>
                    <div>
                        Power User Access
                    </div>
                    <div>
                        <input placeholder="Enter Active Directory group name" type="text" name="powerUserGroup" value="<?php echo  $appConfig["userMappings"]["power"];?>">
                    </div>
                </div>
                <div>
                    <div>
                        Admin User Access
                    </div>
                    <div>
                        <input placeholder="Enter Active Directory group name" type="text" name="adminUserGroup" value="<?php echo  $appConfig["userMappings"]["admin"];?>">
                    </div>
                </div>
                <div>
                    <div>
                        Tech User Access
                    </div>
                    <div>
                        <input placeholder="Enter Active Directory group name" type="text" name="techUserGroup" value="<?php echo  $appConfig["userMappings"]["tech"];?>">
                    </div>
                </div>
                
            </div>
            
    </div>