

<div class="settingsContainer">
        <div  class="settingsList">
		<div>
            <strong>
                Staff Email Groups</strong><br/>
				Do not include the domain name
				</div>
				<div>
            <textarea placeholder="Enter staff email groups, one per line" class="settingsList" name="staffEmailGroups" rows="10" spellcheck="false"><?php
                foreach($appConfig["staffEmailGroups"] as $group){

                    echo $group."\n";

                }

                ?></textarea>
				</div>
         
           </div>
    </div>