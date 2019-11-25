
<div class="settingsContainer">
        <div  class="settingsList">
		<div>
            <strong>
                Admin Usernames</strong><br/>Users in this list will not be able to<br/>be modified via the tools on this site.<br/>

</div>
<div>
            <textarea placeholder="Enter list of usernames, one per line." class="settingsList" name="adminUsernames" rows="5" spellcheck="false"><?php

                foreach($appConfig["adminUsernames"] as $admin){

                    echo $admin."\n";

                }

                ?></textarea>
</div>
            </div>
    </div>