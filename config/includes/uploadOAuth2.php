<div class="settingsContainer">
        <div  class="settingsList">
                <div>
                    <strong>Upload an oauth2.txt from another GAM install?</strong>
                    

                </div>
				<div>
                        <input type="file" id="oauth2_txt" name="oauth2_txt"/>
				</div>
		</div>
</div>

<?php
				if (file_exists("./lib/gam-64/oauth2.txt")){
				?>
		<div class="settingsContainer">
        <div  class="settingsList">		
				<div>
				<strong>Overwrite existing file?</strong>
                    

                </div>
				<div>
                        <input type="checkbox" id="overwrite" name="overwrite"/>
                    </div>
				
				
               
			</div>
            </div>
			
			<?php
				}
				?>
				