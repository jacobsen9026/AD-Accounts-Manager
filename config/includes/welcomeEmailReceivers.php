
<div class="settingsContainer">
        <div  class="settingsList">
		<div>
            <strong>
                Welcome Email Recipients</strong><br/>Blinded all welcome notification emails.
				
				</div>
				<div>


            <textarea placeholder="Enter list of emails, one per line." class="settingsList" name="welcomeEmailReceivers" rows="15" cols="31" spellcheck="false"><?php

                foreach($appConfig["welcomeEmailReceivers"] as $admin){

                    echo $admin."\n";

                }

                ?></textarea>

            </div>
			</div>
            
    </div>