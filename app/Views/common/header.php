

<?php
/*
if($_SESSION["authenticated_basic"]=="true" and !isset($grab)){
	//Load waiting animation that consumes the screen during operations and debug console.
	include("./app/includes/pageLoader.php");
	include("./app/includes/sessionTimeoutWarning.php");
	//echo "./app/views".$grab;	
}



if(isset($_SESSION['authenticated_tech']) and !isset($grab)){
	if($_SESSION["authenticated_tech"]=="true"){
		if ($appConfig["debugMode"]){
				include("./app/includes/debugConsole.php");
				include("./app/includes/debugConfig.php");
				include("./app/includes/debugConsole.php");
				include("./app/includes/debugInclude.php");
				*/
?>

<div class="debugFloatingToolsContainer">
	<div title="Debug Mode is On" onmouseover="hoverOverEditButton(this);" onmouseleave="revertEditButton(this);" onclick="window.open('/?goto=/config/index.php#dm_input');" class="floatingButton">
		<img src="/img/warning2.png"/>
	</div>
	<div title="Open Debug Console" onmouseover="hoverOverEditButton(this);" onmouseleave="revertEditButton(this);" onclick='document.getElementById("debugConsoleContainer").style="visibility:visible";' class="floatingButton">
		<img src="/img/console.png"/>
	</div>
	<div title="Open Config Debug" onmouseover="hoverOverEditButton(this);" onmouseleave="revertEditButton(this);" onclick='document.getElementById("debugConfigContainer").style="visibility:visible";' class="floatingButton">
		<img src="/img/config.png"/>
	</div>
	<div title="Open Config Includes" onmouseover="hoverOverEditButton(this);" onmouseleave="revertEditButton(this);" onclick='document.getElementById("debugIncludeContainer").style="visibility:visible";' class="floatingButton">
		<img src="/img/console.png"/>
	</div>
</div>
<?php
		//}
	//}
//}



?>

