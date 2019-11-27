
<?php



if(strpos($pageURL,"/editor")==false){
    if(isset($goto)){
        if ($goto != "" && $goto!="/"){
            if(file_exists("./help".$goto)){
?>

<a href='/?goto=/help<?php echo str_replace("/?goto=","",$pageURL);?>'>
    <div onmouseover="hoverOverHelpButton(this);" onmouseleave="revertHelpButton(this);" class="floatingHelpButton">

        <img src="/img/help.png"/>

    </div>
</a>
<?php
                                           }
        }
    }
}

if(isset($_SESSION['authenticated_tech'])){
	if($_SESSION["authenticated_tech"]=="true"){
		if ($appConfig["debugMode"] and $_SESSION["authenticated_tech"]=="true"){
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
		}
	}
}



?>
<script>
    onLoad();
</script>




</div>

<?php
if (isset($_SESSION["authenticated_tech"]) and $appConfig["debugMode"] and $_SESSION["authenticated_tech"]=="true"){
    include("./app/includes/debugInclude.php");
}
?>


<!--
Site Written By: Chris Jacobsen
With Credit to Codiad and IceCoder
Creation: September-October 2017
Updated: October 2019
-->

</body>

</html>


