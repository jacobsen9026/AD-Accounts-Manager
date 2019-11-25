
<div class="settingsContainer">
        <div  class="settingsList">
		<div>
            <strong>
                Parent Email Groups</strong><br/>
				Do not include the domain name
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