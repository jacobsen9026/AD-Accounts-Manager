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

if(!file_exists("./app/config/config.json")){
	//exit();
    initializeConfig();
}




if (isset($_GET["advancedConfig"])){
    //include("./config/includes/configController.php");
    include("./app/views/config/index.php");
    //phpinfo();

}
    include ("./app/views/install/welcome.php");

?>



