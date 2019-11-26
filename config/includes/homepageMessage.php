
<div class="settingsContainer">
    <div  class="settingsList">
        <div>
            <h3>
                Homepage Message</h3>
            <small>Accepts HTML and inline style</small>

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