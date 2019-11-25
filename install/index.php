<?php
//Redirect to login page after completing install.
if(isset($_POST["complete_install"])){   
	$appConfig["installComplete"]=true;
	saveConfig();
?>
<script>
    window.location="/";
</script>
<?php
}

if(!file_exists("./config/config.json")){
	intializeConfig();
}





if (isset($_GET["advancedConfig"])){
	//include("./config/includes/configController.php");
	include("./config/index.php");
	//phpinfo();
	
}else{
include ("./install/welcome.php");
	}
?>



