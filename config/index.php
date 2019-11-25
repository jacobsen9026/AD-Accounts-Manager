<?php
global $appConfig;
//debug($_GET["config"]);
if(isset($_GET["config"])){
    $config=$_GET["config"];
}else{
    $config="webApplicationSettings";
}
?>

		

<table id="container">
<form action="<?php echo $pageURL;?>" method="post">
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
		</form>
		<?php
    }
	if(isset($_GET["advancedConfig"])){
    ?>
	<form action="/" method="post">
	<tr>
	<td>
	<br/>
		<button type="submit">Go Back</button>
	</td>
	</tr>
	
	<?php
	}
	?>
</table>

