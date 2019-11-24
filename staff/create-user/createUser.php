
<table class="responseContainer" id="container">

<tr>
<th>
Staff New User Creator
</th>
</tr>
<tr>
<td>
 <?php 


	if ($_POST["firstname"]!=""){
		if ($_POST["lastname"]!=""){
			if ($_POST["middlename"]==""){
			$_POST["middlename"]="NULL";
			}
			$first=true;
			foreach ($staffEmailGroups as $group){
				if($_POST[$group]==$group){
					if($first){
					$googleGroups=$group;	
					$adGroups = getADGroupFromGAGroup($group);
					$first=false;
					}else{
					$googleGroups=$googleGroups.",".$group;
					$adGroups = $adGroups.",".getADGroupFromGAGroup($group);
					}
				}	
				
			}
			
			$googleGroups=$googleGroups.",classroom_teachers";
			
			if($_POST["primaryLocation"]=="BCMS"){
				
				$adGroups = $adGroups.",ct teachers";
				
			}
			if($_POST["primaryLocation"]=="SBS"){
				
				$adGroups = $adGroups.",sb teachers";
			}
			if($_POST["primaryLocation"]=="WES"){
				
				$adGroups = $adGroups.",wt teachers";
			}
			//echo $googleGroups;
			//echo "would run<br/>";
			$cmd = "powershell.exe ./staff/utils/CreateUsers.ps1 '".$_POST["firstname"]."' '".$_POST["middlename"]."' '".$_POST["lastname"]."' '".$googleGroups."' '".$adGroups."' '".$_POST["primaryLocation"]."'";
			//echo $cmd;
			$result = shell_exec($cmd);
			echo $result;
			if(strpos($result,"Account Creation Successful")!=false){
				sendWelcomeEmail(substr($_POST["firstname"],0,1).$_POST["lastname"]);
				echo "<br/> Welcome Email Sent";
			}
		}else{
		echo "Missing Last Name.";
		}
	}else{
	echo "Missing First Name.";
	}












?>
<br/><br/>

<a href='/?goto=/staff/create-user/index.php'><button type='button' value='Create Another'>Create Another</button></a>
</td>
</tr>
</table>
