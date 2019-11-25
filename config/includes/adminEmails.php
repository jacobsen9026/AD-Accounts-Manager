
<div class="settingsContainer">
        <div  class="settingsList">
		<div>
            <strong>
                Admin Email Addresses</strong><br/>Recieves all notification emails.
				
				</div>
				<div>


            <textarea placeholder="Enter list of emails, one per line." class="settingsList" name="adminEmails" rows="5" spellcheck="false"><?php

                foreach($appConfig["adminEmails"] as $admin){

                    echo $admin."\n";

                }

                ?></textarea>
</div>
</div>
            
    </div>