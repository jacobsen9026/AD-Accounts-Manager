
<div class="settingsContainer">
    <div  class="settingsList">
        <div>
            <h3>
                Welcome Email Recipients</h3>
            <small>Blinded all welcome notification emails.</small>

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