<table class="responseContainer">

<tr>
<th>
Student Account Status
</th>
</tr>
<tr>
<td sytle="text-align:center;font-size:1em;">
 <?php 
 if ($_POST["username"]!=""){	
 if(substr($_POST["username"],0,2)>21 and substr($_POST["username"],0,2)<99){
 
 
if(strpos($_POST["username"],",")!=false){
	//Start Multi User
	$users=explode(",",$_POST["classroomID"]);
	foreach($classrooms as $class){
		if ($_POST["classroomID"]!=""){
			if(strpos($_POST["classroomID"],",")!=false){
				//Start Multi User Multi Classroom
				$classrooms=explode(",",$_POST["classroomID"]);
				foreach($classrooms as $class){
						if($_POST["action"]=="add"){
					$cmd = "gam course ".$class." add student ".$_POST["username"]."@".$appConfig["domainName"] ;
					//echo $cmd;
					$result = shell_exec($cmd);
						echo $result."<br/>";
					}else{
						$cmd = "gam course ".$class." remove student ".$_POST["username"]."@".$appConfig["domainName"] ;
						$result = shell_exec($cmd);
						echo $result."<br/>";
					}
						
						
				}
				
				
				}else{
					//Start Multi User Single Classroom
					if($_POST["action"]=="add"){
						$cmd = "gam course ".$_POST["classroomID"]." add student ".$_POST["username"]."@".$appConfig["domainName"];
						//echo $cmd;
						$result = shell_exec($cmd);
						echo $result;
					}else{
						$cmd = "gam course ".$_POST["classroomID"]." remove student ".$_POST["username"]."@".$appConfig["domainName"] ;
						$result = shell_exec($cmd);
						echo $result;
					}
				}
			?>
			<table style="width:50%;margin-right:auto;margin-left:auto">
			<tr>
			<td>
			<form action="/?goto=/students/classroom/manageGoogleClassroom.php" method="post">
			<input name="username" value="<?php echo $_POST["username"];?>" hidden>
			<br/><br/>
			Add Students To Another Class<br/>
			Classroom ID (Course Code/Section Number)<br/>
			<input type="text" name="classroomID"><br/>
			Action<br/>
			<select name="action">
			  <option value="add">Add</option>
			  <option value="remove">Remove</option>
			</select><br/>
			<div id="runText2" style="color:red">&nbsp </div><br/>
			<button id="submitButton2" onclick="showMessege2('Gathering student account status please wait...')" type="submit" value="Add/Remove this student to another classroom">Add/Remove this student to another classroom</button>
			
			
			
			
			</form>
			</td>
			</tr>
			</table>
			<?php
			//echo $result;
		}else{
		echo "Missing Classroom ID.";
		}
	}
 }else{
 	//Start Single User
	if ($_POST["classroomID"]!=""){
		if(strpos($_POST["classroomID"],",")!=false){
			//Start Single User Multi Classroom
			$classrooms=explode(",",$_POST["classroomID"]);
			foreach($classrooms as $class){
					if($_POST["action"]=="add"){
				$cmd = "gam course ".$class." add student ".$_POST["username"]."@".$appConfig["domainName"] ;
				//echo $cmd;
				$result = shell_exec($cmd);
					echo $result."<br/>";
				}else{
					$cmd = "gam course ".$class." remove student ".$_POST["username"]."@".$appConfig["domainName"] ;
					$result = shell_exec($cmd);
					echo $result."<br/>";
				}
					
					
			}
			
			
			}else{
				//Start Single User Single Classroom
		if($_POST["action"]=="add"){
			$cmd = "gam course ".$_POST["classroomID"]." add student ".$_POST["username"]."@".$appConfig["domainName"] ;
			//echo $cmd;
			$result = shell_exec($cmd);
			echo $result;
		}else{
			$cmd = "gam course ".$_POST["classroomID"]." remove student ".$_POST["username"]."@".$appConfig["domainName"] ;
			$result = shell_exec($cmd);
			echo $result;
		}
			}
		?>
		<table style="width:50%;margin-right:auto;margin-left:auto">
		<tr>
		<td>
		<form action="/?goto=/students/classroom/manageGoogleClassroom.php" method="post">
		<input name="username" value="<?php echo $_POST["username"];?>" hidden>
		Classroom ID (Course Code/Section Number)<br/>
		<input type="text" name="classroomID"><br/>
		Action<br/>
		<select name="action">
		  <option value="add">Add</option>
		  <option value="remove">Remove</option>
		</select><br/>
		<div id="runText2" style="color:red">&nbsp </div><br/>
		<input id="submitButton2" onclick="showMessege2('Gathering student account status please wait...')" type="submit" value="Add/Remove this student to another classroom.">
		
		
		
		
		</form>
		</td>
		</tr>
		</table>
		<?php
		//echo $result;
	}else{
	echo "Missing Classroom ID.";
	} 
 }
 }else{
	 echo "This can only be used for students";
 }
}else{
echo "Missing Username.";
}
?>
<br/><br/>
<a href='/?goto=/students/classroom/index.php'><button type='button' value='Back'>Back</button></a>
</td>
</tr>
</table>
