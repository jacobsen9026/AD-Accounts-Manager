
<table class="responseContainer">

<tr>
<th>
Student H-Drive Utility
</th>
</tr>
<tr>
<td>
 <?php 
	if ($_POST["username"]!=""){
		$username = $_POST["username"];
		if(substr($username,0,2)>21 and substr($username,0,2)<99){
			
		if(doesADUserExist($username)==true){
			
			if($_POST["action"]=="query"){
				$result = getADUserHDrive($username);
				echo "H-Drive Path: ".$result;
				
			}elseif($_POST["action"]=="fix"){
			
			$result = fixADUserHDrive($username);
			echo $username."<br />";
				echo $result;
			
			
			
			}
		}else{
			echo "Username not found in the ".$appConfig['domainNetBIOS']." Network.";
		}
		}else{
		echo "This can only be used for students.";
		}
	}else{
	echo "Missing username.";
	}


?>
<br/><br/>
<!--
<input onclick="CopyToClipboard('username')" type="button" value="Copy Username">
<input onclick="CopyToClipboard('password')" type="button" value="Copy Password">
-->
<a href='/?goto=/students/h-drive/index.php'><button type='button' value='Back'>Back</button></a>
</td>
</tr>
</table>
