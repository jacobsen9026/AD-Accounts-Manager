
<div class="settingsContainer">
    <div  class="settingsList">
        <div>
            <h3>
                Parent Email Groups</h3>
            <small>
                Do not include the domain name</small>
        </div>
        <div>
            <textarea  placeholder="Enter parent email groups, one per line" class="settingsList" name="parentEmailGroups" rows="10" spellcheck="false"><?php
                foreach($appConfig["parentEmailGroups"] as $group){

                    echo $group."\n";

                }

                ?></textarea>
        </div>
    </div>
</div>