
<table class="responseContainer" id="container">

<tr>
<th>
Staff Password Reset
</th>
</tr>
<tr>
<td>
 <?php 
//echo "test";
//$cmd = "CreateUsers.ps1 ".$_POST["yog"]." ".$_POST["firstname"]." ".$_POST["middle"]." ".$_POST["last"]." ".$_POST["grade"]." ".$password;
//echo $_POST["yog"]."<br/>";
//echo $_POST["firstname"]."<br/>";
//echo $_POST["lastname"]."<br/>";
global $appConfig;
$username=$_POST["username"];
if ($username!=""){
	if(notProtectedUsername($_POST["username"])){
		if(strlen($_POST["password"])>6){
			$password=$_POST["password"];
			$resetOnNextLogon = "false";
		}else{
			$password=$appConfig['defaultStaffPassword'];
			$resetOnNextLogon = "true";
		}
			if($_POST["reset_ad"]=="yes" and $_POST["reset_google"]=="yes"){
				$ADExist=doesADUserExist($username);
				$GAExist=doesGAUserExist($username);
				if($ADExist and $GAExist){
						$ADresult=resetADUserPassword($username,$password,$resetOnNextLogon);
						$GAresult=resetGAUserPassword($username,$password,$resetOnNextLogon);
						//echo $cmd;
						if($GAresult==false){
							echo "There was an error setting the ".$appConfig['domainNetBIOS']." password.<br />";
							
						}
						if($ADresult==false){
							echo "There was an error setting the ".$appConfig['domainNetBIOS']." password.<br />";
							
						}
				}else{
					if($ADExist==false){
					echo "<br />Username not found in ".$appConfig['domainNetBIOS']."<br />";	
					}
					if($GAExist==false){
						echo "<br />Username not found in Google<br />";
					}
					
				}
				//echo "would run<br/>";
			}elseif($_POST["reset_ad"]=="yes"){
				$ADExist=doesADUserExist($username);
				
				if($ADExist){
					
					$ADresult=resetADUserPassword($username,$password,$resetOnNextLogon);
			
					
					if($ADresult==false){
						echo "There was an error setting the ".$appConfig['domainNetBIOS']." password.<br />";
						
					}
					//echo $ADresult;
					
					
					//$result = shell_exec($cmd);
				}else{
					echo "Username not found in ".$appConfig['domainNetBIOS'],"<br />";
					
				}
			}elseif($_POST["reset_google"]=="yes"){
				$GAExist=doesGAUserExist($username);
				if($GAExist){
					$GAresult = resetGAUserPassword($username,$password,$resetOnNextLogon);
					if($GAresult==false){
							echo "There was an error setting the ."$appConfig['domainNetBIOS']." password.<br />";
							
						}
					//echo $cmd;
			
					//$result = shell_exec($cmd);
				}else{
					echo "Username not found in Google<br />";
					
				}
			}else{
				
				echo "No system selected!";
				
			}
			//echo $cmd;
	
			
			if($GAExist or $ADExist){
				
				$message="$username has had their password reset to $password<br/>Systems:<br/>";
				if($_POST["reset_ad"]){
					$message=$message.$appConfig['domainNetBIOS']."<br/>";
				}
					if($_POST["reset_google"]){
					$message=$message."Google<br/>";
				}
				sendNotificationEmail("Staff Password Reset",$message);
				
				echo "<strong>New Staff Credentials</strong><br />";
				
				echo "<br />Username: <text id='username' onclick='copyThis(this);'>".$username."</text><br/>";
				
				echo "Pasword: <text id='password' onclick='copyThis(this);'>".$password."</text><br/>";
				echo "<br />Changed For:<br />";
				if($_POST["reset_ad"]=="yes"){
					echo $appConfig['domainNetBIOS']."<br/>";
					
				}
				if($_POST["reset_google"]=="yes"){
					echo "Google<br/>";
					
				}
			}
	}else{
	echo "That user is protected.";
	}
	
}else{
echo "Missing Username.";
}











?>
<br/><br/>
<button onclick="CopyToClipboard('username')" type="button" value="Copy Username">Copy Username</button>
<button onclick="CopyToClipboard('password')" type="button" value="Copy Password">Copy Password</button>
<a href='/?goto=/staff/password-reset/index.php'><button type='button' value='Reset Another'>Reset Another</button></a>
</td>
</tr>
</table>
