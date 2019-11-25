<?php
//var_export($_POST);
if(isset($_POST["complete_install"])){
    //echo $_POST["complete_install"];
	//exit();
    
	initializeConfig();
?>
<script>
    window.location="/";
</script>
<?php
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



if (isset($_GET["advancedConfig"])){
	//include("./config/includes/configController.php");
	include("./config/index.php");
	//phpinfo();
	
}else{
include ("./install/welcome.php");
	}
?>



