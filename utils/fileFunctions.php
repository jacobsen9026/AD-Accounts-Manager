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
                if(strpos(" ".$file,"siteVariables.php")==false){
                    if(filemtime($src . '/' . $file)>filemtime($dst . '/' . $file)){
                        copy($src . '/' . $file,$dst . '/' . $file); 
                        clearstatcache();
                        touch($src . '/' . $file,filemtime($dst.'/'.$file));
                    }
                }
            } 
        } 
    } 
    closedir($dir); 
}
function getViews($path){
	$views = scandir($path."/views/");
	$folders = Array();
	foreach ($views as $folder){
		if ($folder!="." and $folder != ".." and strpos($folder ,".")==null){
			$folders[]=$folder;
			//echo $folder;
		}
		
	}
	return $folders;
		
}

function getViewFiles($viewPath){
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

function auditLogon($username){
	$dir="./logs/";
	$file="./logs/login.log";
	
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
    include("./config/siteVariables.php");
    if(isset($_SESSION["authenticated_tech"])){

        if($_SESSION["authenticated_tech"]=="true"){

            if(isset($path)){
                $folder= substr($path,0,strlen($path)-(strlen($path)-strrpos($path,"/")));

                if(!file_exists(".".$folder)){
                    mkdir(".".$folder, 0740, true);
                    if(copy("./page-template.php",".".$path)){

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
	$appConfig["configuredVersion"]=file_get_contents("./version.txt");
	$appConfig["domainNetBIOS"]=$_SERVER['USERDOMAIN'];
	saveConfig();
}

function saveConfig(){
    global $appConfig;
	
    ksort($appConfig);
    $dateTime=date("Y-m-d_h-i-s");
    if(!file_exists($_SERVER['DOCUMENT_ROOT']."/config/backup/")){
        mkdir($_SERVER['DOCUMENT_ROOT']."/config/backup/", 0740, true);
    }
    copy ($_SERVER['DOCUMENT_ROOT']."/config/config.json" , $_SERVER['DOCUMENT_ROOT']."/config/backup/".$dateTime."_config.json");
    file_put_contents("./config/config.json", json_encode($appConfig));
}
function loadConfig(){
    global $appConfig;
    $appConfig = json_decode(file_get_contents("./config/config.json"),true);
	$appConfig["version"] = file_get_contents("./version.txt");
	
	 
	 
	 ksort($appConfig);
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

?>