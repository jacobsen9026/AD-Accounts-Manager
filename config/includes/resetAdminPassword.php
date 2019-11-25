
 <div class="shortSettingsContainer">
            <div  class="settingsList">
                <div>
                    <h3>
                        Set New Admin Password
                    </h3>

                </div>
                <div>

                        <input title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder="<?php if(!isset($appConfig['adminPassword'])){echo 'Not Yet Set';}else{echo'Enter new password';}?>" type="password" name="adminPassword" value="">
                    
                </div>
                
            </div>
           
    </div>