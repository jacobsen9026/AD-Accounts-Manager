
<div class="settingsContainer">
    <div  class="settingsList">
        <div>
            <h3>
                Admin Email Addresses</h3>
            <small>Recieves all notification emails.</small>

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