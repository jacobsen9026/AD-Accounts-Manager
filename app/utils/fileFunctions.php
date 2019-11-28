<?php
function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                
				if(filemtime($src . '/' . $file)>filemtime($dst . '/' . $file)){
					copy($src . '/' . $file,$dst . '/' . $file); 
					clearstatcache();
					touch($src . '/' . $file,filemtime($dst.'/'.$file));
				}
                
            } 
        } 
    } 
    closedir($dir); 
}

function getFolders($path){
    $views = scandir($path."/");
    $folders = Array();
    foreach ($views as $folder){
        if ($folder!="." and $folder != ".." and strpos($folder ,".")==null){
            $folders[]=$folder;
            //echo $folder;
        }

    }
    return $folders;

}



function getFiles($viewPath){
    $views = scandir($viewPath);
    $files = Array();
    foreach ($views as $file){
        if ($file!="." and $file != ".." and strpos($file ,".")!=null){
            $files[]=$file;
            //echo $folder;
        }

    }
    return $files;

}

function savePowershellCredentials($username, $password){
	global $appConfig;
	$passwordFilePath="./app/config/adCred.key";
	$appConfig["powershellPassword"] = encryptADPassword($password)[0];
	//echo "teresres";
	//exit;
	saveConfig();
	//file_put_contents($passwordFilePath,
}



function auditLogon($username){
    $dir="./app/logs/";
    $file="./app/logs/login.log";

    $date=date("Y/m/d");
    $time=date("h:i:s");

    if(!file_exists($dir)){
        mkdir($dir);
    }
    if(!file_exists($file)){
        $logFile=fopen($file,"w");
        fwrite($logFile,"Date,Time,User\r\n");
        fclose();
    }
    file_put_contents($file,$date.",".$time.",".$username."\r\n", FILE_APPEND);

}

function createNewPage($path){
    if(isset($_SESSION["authenticated_tech"])){

        if($_SESSION["authenticated_tech"]=="true"){

            if(isset($path)){
                $folder= substr($path,0,strlen($path)-(strlen($path)-strrpos($path,"/")));

                if(!file_exists(".".$folder)){
                    mkdir(".".$folder, 0740, true);
                    if(copy("./app/page-template.php",".".$path)){

                        return true;
                    }
                }else{
                    return false;
                }

            }

        }

    }
    return false;

}

function initializeConfig(){
    global $appConfig;
    $appConfig["sessionTimeout"]=1200;
    $appConfig["configuredVersion"]=file_get_contents("./app/version.txt");
    $appConfig["domainNetBIOS"]=$_SERVER['USERDOMAIN'];
    saveConfig();
}

function saveConfig(){
    global $appConfig;
	$configDir = "./app/config";
    ksort($appConfig);
    $dateTime=date("Y-m-d_H-i-s");
    if(!file_exists($configDir."/backup/")){
        mkdir($configDir."/backup/", 0740, true);
    }
    copy ($configDir."/config.json" , $configDir."/backup/".$dateTime."_config.json");
    file_put_contents($configDir."/config.json", json_encode($appConfig));
}
function loadConfig(){
    global $appConfig;
	$configDir = "./app/config";
	if(file_exists($configDir."/config.json")){
		$appConfig = json_decode(file_get_contents($configDir."/config.json"),true);
		$appConfig["version"] = file_get_contents("./app/version.txt");



		ksort($appConfig);
	}
}




function isGAMConfigured(){
    $result=runGAMCommand("info domain")[0];
    debug(strpos($result,"ID"));
    //echo $result;
    if(strpos($result,"ID")>0){
        return true;
    }
    return false;

}
function isGAMAuthorized(){

    if(file_exists("./lib/gam-64/ouath2.txt")){
        return true;
    }
    return false;
}
function isGAMCredentialReady(){

    if(file_exists("./lib/gam-64/client_secret.json") and file_exists("./lib/gam-64/oaut2service.json")){
        return true;
    }
    return false;
}

function isGitAvailable(){
    $result=shell_exec("git");
    if(strpos($result,"--version")>0){
        return true;
    }
    return false;
}

function isPowershellAvailable(){
    $result=shell_exec("powershell.exe /?");
    if(strpos($result,"-Version")>0){
        return true;
    }
    return false;

}

function isPowershellADAvailable(){
    $result=shell_exec("git");
    if(strpos($result,"-Version")>0){
        return true;
    }
    return false;

}


function delete_directory($dirname) {
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname."/".$file))
                unlink($dirname."/".$file);
            else
                delete_directory($dirname.'/'.$file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

function takeFullBackup(){
	$dateTime=date("Y-m-d_H-i-s");
	copy ($configDir."/config.json" , $configDir."/backup/".$dateTime."_config.json");
    if(!file_exists($configDir."/backup/")){
        mkdir($configDir."/backup/", 0740, true);
    }
	$zip = new ZipArchive;
	if ($zip->open($configDir.'/backup/'.$dateTime.'-backup.zip') === TRUE) {
		$zip->addFile('./*');
		$zip->close();
	}
}

function updateApp(){
	takeFullBackup();
	
	$zip = new ZipArchive;
    if(!file_exists("./temp")){
        mkdir("./temp");
    }
    copy ("https://github.com/jacobsen9026/School-Accounts-Manager/archive/master.zip", "./temp/update.zip");

    
    if ($zip->open("./temp/update.zip") === TRUE) {
        $zip->extractTo('./update');
        $zip->close();
        recurse_copy ("./update/School-Accounts-Manager-master","./");
    }
    delete_directory("./temp");
    delete_directory("./update");
    loadConfig();
    $appConfig["configuredVersion"]=$appConfig["version"];
    saveConfig();
}


?>