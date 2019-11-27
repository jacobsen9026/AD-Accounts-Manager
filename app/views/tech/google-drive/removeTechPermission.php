
<table class="responseContainer">
<tr>
<th>
Google Drive Manager
</th>
</tr>
<tr>
<td>
<br/><br/>
<?php
if(isset($_POST["targetURL"])){
	if(isset($_POST["username"])){
	if($_POST["targetURL"]!=""){
		if($_POST["username"]!=""){
			$username = $_POST["username"];
		$target = $_POST["targetURL"];
		if (strpos($target,".google.com/")!=false){
			if(strpos($target,"/d/")!=false){
				//echo strpos($target,"/d/");
				$rightTarget = substr($target,strpos($target,"/d/")+3);
				//echo $rightTarget;
				if(strpos($rightTarget,"/")!=false){
					$rightTarget=substr($rightTarget,0,strlen($rightTarget)-(strlen($rightTarget)-strpos($rightTarget,"/")));
					
				}
				//echo $rightTarget;
				if(removeTechDrivePermission($username,$rightTarget)){
					?>
				
				Removed permissions from file.<br/><br/>
				<a href="/?goto=/tech/google-drive/index.php">
					<button type="button">Back</button>
				</a>
					<?php
				}else{
					echo "There was an error.";
					
				}
				
			}else{
				echo "That doesn't look like the right link.";
			}
			
		}else{
			echo "That doesn't look like a Google Drive link!";
		}
		}else{
			echo "Missing username.";
		}
	}else{
	echo "Missing the URL."	;
		
	}
	}
}
?>
<br/><br/>
</td>
</tr>
</table>
