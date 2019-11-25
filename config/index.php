<?php
global $appConfig;
//debug($_GET["config"]);
if(isset($_GET["config"])){
    $config=$_GET["config"];
}else{
    $config="webApplicationSettings";
}
?>

		<form action="<?php echo $pageURL;?>" method="post">

<table id="container">
    <tr>
        <td>
            <?php
            include("./config/includes/configNavBar.php");
            ?>
        </td>
		</tr>

	
    <?php
    debug($config);
	include("./config/includes/configController.php");
    if(file_exists("./config/views/".$config.".php")){
        debug("File Exists");
		
        include("./config/views/".$config.".php");
		?>
		<tr>
			<td>
				<button type="submit">Update Settings</button><br/>
			</td>
		</tr>
		<?php
    }
    ?>
</table>
</form>
