<table class="responseContainer">

<tr>
<th>
Google Classroom Status
</th>
</tr>
<tr>
<td sytle="text-align:center;font-size:1em;">
 <?php 
if ($_POST["classroomID"]!=""){
	$inParticipants = false;
	$cmd = "gam info course ".$_POST["classroomID"] ;
	$result = shell_exec($cmd);
	if($result!=""){
	foreach(explode("\n",$result) as $line){
		if(strpos($line,"name:")!==false){
			echo "<strong>Name</strong>: ".substr($line,strpos($line,": ")+2)."<br/>";
		}elseif(strpos($line,"section:")!==false){
			echo "<strong>Section</strong>: ".substr($line,strpos($line,": ")+2)."<br/>";
			
		}
		elseif(strpos($line,"Participants:")!==false){
			$inParticipants = true;
			echo "<br/>";
			
		}elseif($inParticipants){
			if(strpos($line,"Students:")!==false){
				echo "<br/>";
				$numberOfStudent=-2;
			}
			$numberOfStudent++;
			echo $line."<br/>";
		}
		#echo $line."<br/>";
	}
	echo "<br/> Number of students: ".$numberOfStudent;
	//echo $result;
	}else{
		echo "Classroom not found.";
	}
}else{
echo "Missing Classroom ID.";
}
?>
<br/><br/>

<div class="centered">
<a href='/?goto=/students/classroom/index.php'><button type='button' value='Back'>Back</button></a>
</div>
</td>
</tr>
</table>
