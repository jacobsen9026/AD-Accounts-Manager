
<table class="responseContainer">

<tr>
<th>
Student Password Reset
</th>
</tr>
<tr>
<td>
 <?php 
$username =$_POST["username"];
if ($username!=""){
	if(substr($username,0,2)>"21" and substr($username,0,2)<"99"){
		
		
		
		
		
			$yog = substr($username,0,2);
			//echo $yog;
			$grade = getGradeFromYOG("20".substr($username,0,2));
			//echo $grade;
			$password=generateNewStudentPassword($grade);
			//echo $password;
			//exit;
			
			
			
			if(strlen($_POST["password"])>6){
				$password=$_POST["password"];
			}else if (strlen($_POST["password"])>0){
				echo "Generating password as supplied password was too short.<br/>";
			}
			
			
			//echo "GRADE: ".$grade;
			switch($grade){
				case "8":
				case "7":
				case "6":
					$resetPassword = "false";
					break;
				case "5":
				case "4":
					$resetPassword = "false";
					break;
				case "3":
				case "2":
				case "1":
				case "K":
				case "PK4":
				case "PK3":
					$resetPassword = "false";
					break;
					
				default:
					$resetPassword = "false";
					break;
			}
			//echo "resetPassword: ".$resetPassword;
			
			
			
			
			
			if($_POST["reset_google"]=="yes" and $_POST["reset_ad"]=="yes"){
			
				$ADExist=doesADUserExist($username);
				$GAExist=doesGAUserExist($username);
				//var_dump($ADExist);
				//var_dump($GAExist);
				//exit;
				if($ADExist and $GAExist){
					
			
			
				$ADresult=resetADUserPassword($username,$_POST["password"],$resetPassword);
				$GAresult=resetGAUserPassword($username,$_POST["password"],$resetPassword);
				//echo $cmd;
				if($GAresult==false){
					echo "There was an error setting the Google password.<br />";
					
				}
				if($ADresult==false){
					echo "There was an error setting the ".$appConfig['domainNetBIOS']." password.<br />";
					
				}
				//$result = shell_exec($cmd);
				}else{
					if($ADExist==false){
					echo "<br />Username not found in ".$appConfig['domainNetBIOS']."<br />";	
					}
					if($GAExist==false){
						echo "<br />Username not found in Google<br />";
					}
					
				}
			}elseif($_POST["reset_google"]=="yes"){
				//echo "would run<br/>";
			
				$GAExist=doesGAUserExist($username);
				if($GAExist){
					$GAresult = resetGAUserPassword($username,$password,$resetPassword);
					if($GAresult==false){
							echo "There was an error setting the ".$appConfig['domainNetBIOS']." password.<br />";
							
						}
					
				}else{
					echo "Username not found in Google<br />";
					
				}
			}elseif($_POST["reset_ad"]=="yes"){
				$ADExist=doesADUserExist($username);
				
				if($ADExist){
					
					$ADresult=resetADUserPassword($username,$password,$resetPassword);
			
					
					if($ADresult==false){
						echo "There was an error setting the ".$appConfig['domainNetBIOS']." password.<br />";
						
					}
					//echo $ADresult;
					
					
					//$result = shell_exec($cmd);
				}else{
					echo "Username not found in ".$appConfig['domainNetBIOS']."<br />";
					
				}
			}else{
				echo "No system selected!";
				
			}
			
			
			
			
			
			
			
			
			
			
			
			
			if($GAExist or $ADExist){
				
				$message="$username has had their password reset to $password<br/>Systems:<br/>";
				if($_POST["reset_ad"]){
					$message=$message.$appConfig['domainNetBIOS']."<br/>";
				}
					if($_POST["reset_google"]){
					$message=$message."Google<br/>";
				}
				sendNotificationEmail("Student Password Reset",$message);
			echo "<strong>New Student Credentials</strong><br />";
			if ($grade<8 or $grade == "K" or $grade =="PK4" or $grade == "PK4"){
			echo "Please update in Genesis if changed.<br />";
			}
			echo "Username: <text id='username' onclick='copyThis(this);'>".$username."</text><br/>";
			
			echo "Pasword: <text id='password' onclick='copyThis(this);'>".$password."</text><br/>";
			echo "<br />Changed For:<br />";
			if($_POST["reset_ad"]=="yes"){
				echo $appConfig['domainNetBIOS']."<br/>";
				
			}
			if($_POST["reset_google"]=="yes"){
				echo "Google<br/>";
				
			}
			
			echo $result;
		
		
		
		
			}
		
		
		
		
		
	}else{
	echo "This can only be used for students.";	
	
	}
}else{
echo "Missing Username.";
}






	if($GAExist or $ADExist){
?>
<br/><br/>
<div class="centered">
<button onclick="CopyToClipboard('username')" type="button" value="Copy Username">Copy Username</button>
</div>
<br />

<div class="centered">
<button onclick="CopyToClipboard('password')" type="button" value="Copy Password">Copy Password</button>
</div>
<?php
}
?>
<br />

<div class="centered">
<a href='/?goto=/students/password-reset/index.php'><button type='button' value='Reset Another'>Back</button></a>
</div>
</td>
</tr>
</table>
