<form action="/?goto=/tech/google-drive/addTechPermission.php" method="post"> 
<table class="container">
<tr>
<th>
Google Drive Manager
</th>
</tr>
<tr>
<td><br/><strong>Take Ownership</strong>
<br/><br/>


Username With Write Access:<br/>
<input style="max-width:50%" type="text" name="username" autofocus><small>@<?php echo $appConfig["domainName"];?></small><br/>
</td>
</tr>
<tr>
	<td>
Link to target:
<input name="targetURL" />
<br/><br/>
<button onclick="showMessege('Granting technology staff read access to file please wait...')" type="submit">Submit</button>

<br/><br/>
</td>
</tr>
</table>
</form>







<?php

$fileList = scandir($_SERVER['DOCUMENT_ROOT']."/tech/google-drive/history/");
$i=2;
$size= sizeof($fileList);
if($size>2){
?>
	
	<table class="container">
	
	<tr>
	<th>
	History
	</th>
	</tr>
	<?php
		while($i<$size){
		
			$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/tech/google-drive/history/".$fileList[$i]);
			$contents = explode(",",$contents);
	echo "<tr><td><form action='/?goto=/tech/google-drive/removeTechPermission.php' method='post'><input name='username' value='".$contents[0]."' hidden /> <input name='targetURL' value='".$contents[1]."' hidden /><a target='_blank' href='".$contents[2]."'><button type='button'>$contents[0]<br />$fileList[$i]</button></a><button";?> onclick="showMessege('Removing technology staff access to file please wait...')"<?php echo "type='submit'>Remove<br/>Permission</button></form></td></tr>";
		$i++;
		}
	?>
	
	</table>
<?php
}
?>