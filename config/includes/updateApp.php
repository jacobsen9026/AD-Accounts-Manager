<?php
$availableVersion=file_get_contents('https://raw.githubusercontent.com/jacobsen9026/School-Accounts-Manager/master/version.txt');

if(isset($_POST["updateApp"])){	   
	$zip = new ZipArchive;
	if(!file_exists("./temp")){
		mkdir("./temp");
	}
	copy ("https://github.com/jacobsen9026/School-Accounts-Manager/archive/master.zip", "./temp/update.zip");
	
	$res = $zip->open("./temp/update.zip");
	//var_export($res);
	//exit();
	if ($res === TRUE) {
		$zip->extractTo('./update');
		$zip->close();
		//exit();
		recurse_copy ("./update/School-Accounts-Manager-master","./");
		//echo 'ok';
		//exit();
	}
	//unlink("/temp/update.zip");
	delete_directory("./temp");
	delete_directory("./update");
	loadConfig();
	$appConfig["configuredVersion"]=$appConfig["version"];
	saveConfig();
}


if (floatval($availableVersion)>floatval($appConfig["version"])){
?>
 <div class="shortSettingsContainer">
        
            
        <div  class="settingsList">
		<div>
                
                    <h3>
                        Update the Application
                    </h3>
Current Version:<?php echo $appConfig["version"];?><br/>
						Available Version:<?php echo $availableVersion;?><br/><br/>
						<?php

                        if(isset($_POST["updateApp"])){
                            echo"<div class='alert'>Application Updated Succefully!</div>";
                        }
                        ?>
                </div>
                
           
				<div>
			
				Update Application<br/><br/><input type="checkbox" name="updateApp" value="updateApp" /><br/><br/>
            <button id="ap_input" type="submit"  value="Update App">Submit</button><br/>

				
				</div>

            </div>
			
		
    </div>
	
<?php
}
?>