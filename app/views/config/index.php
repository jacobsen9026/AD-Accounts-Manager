<?php
global $appConfig;
//debug($_GET["config"]);
if(isset($_GET["config"]) and $_GET["config"]!=""){
    $config=$_GET["config"];
}else{
    $config="webApplicationSettings";
}

	//echo "<br/><br/><br/>sadjhksakjdhsakjd";

$views = getFolders("./app/views/config/views");
//var_export($views);
foreach($views as $view){
    $viewFile = getFolders("./app/views/config/views/".$view)[0];
	
	//echo $viewFile;
    $viewVariable = explode(".",$viewFile)[0];
	if(isset($viewVariable) and $viewVariable!=""){
		$search[]="&config=".$viewVariable;
	}
    if($viewVariable==$config){
        $currentView=$view;
        //echo $currentView;
    }
}
//var_export($search);
//debug($views);
?>


<form action="<?php echo $pageURL;?>" method="post" enctype="multipart/form-data">
    <table class="container">

        <tr>

            <td>

                <?php
                include("./app/views/config/configNavBar.php");
                ?>

            </td>

        </tr>


        <?php
        //debug($config);
        include("./app/config/configController.php");
        if(file_exists("./app/views/config/views/".$currentView."/".$config)){
            debug("File Exists");
			?>
<!--			
<tr>
    <th>
        <?php echo $currentView;?>
    </th>
</tr>
-->
<tr>
    <td>

        <?php
        foreach(getFiles("./app/views/config/views/".$currentView."/".$config) as $settingTool){
			include("./app/views/config/views/".$currentView."/".$config."/".$settingTool);
		}
        ?>









    </td>
</tr>

			
			
			<?php

            //include("./config/views/".$currentView."/".$config.".php");
        ?>
        <tr>
            <td>
			<br/><br/>
                <button type="submit">Update Settings</button><br/>
            </td>
        </tr>

        <?php
        }
        ?>
        </form>
    <?php
    if(isset($_GET["advancedConfig"])){
    ?>

    <tr>
        <td>
            <br/>
            <a href="/">
                <button type="button">Go Back</button>
            </a>
        </td>
    </tr>
    <?php
    }
    ?>
    </table>
</form>
