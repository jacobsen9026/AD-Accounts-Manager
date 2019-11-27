
<table class="responseContainer">
<tr>
<th>
Rename Workstation
</th>
</tr>
<tr>
<td>
<br/><br/>
<?php
if(isset($_POST["PCName"])){

		if($_POST["PCName"]!=""){

			$PCName = $_POST["PCName"];
			
			$timeDelay = $_POST["delay"];
			if(hostReachable($PCName)){
					echo rebootADComputer($PCName,$timeDelay);
			}else{
				echo "PC not reachable";
			}
	}else{
		echo "Missing PC name.";
	}
}else{
echo "Missing PC name."	;
	
}
	

?>
<br/><br/>
<a href='/?goto=/tech/computers/index.php'><button type='button' value='Back'>Back</button></a>
</td>
</tr>
</table>
