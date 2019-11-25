
<div class="settingsContainer">
        <div  class="settingsList">
		<div>
            <strong>
                Homepage Message</strong><br/>Accepts HTML and inline style
				
				</div>
				<div>


            <textarea placeholder="Enter list of emails, one per line." class="settingsList" name="homepageMessage" rows="5" spellcheck="false"><?php

                foreach($appConfig["homepageMessage"] as $admin){

                    echo $admin."\n";

                }

                ?></textarea>
</div>
</div>
            
    </div>