<?php
//Redirect to login page after completing install.
if(isset($_POST["complete_install"])){   
	initializeConfig();
?>
<script>
    window.location="/";
</script>
<?php
}





if (isset($_GET["advancedConfig"])){
	//include("./config/includes/configController.php");
	include("./config/index.php");
	//phpinfo();
	
}else{
include ("./install/welcome.php");
	}
?>



