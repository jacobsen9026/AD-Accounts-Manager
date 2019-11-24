<td  class="navigationButton">
			
			<button id="staffButton" <?php
			if(strpos($_SERVER['REQUEST_URI'],"/staff/")!== false){
				echo "class='currentPageButtonHighlight'";
			}
				?> onclick="showStaffDropdown()">
				Staff
			</button>
			<div id="staffDropdown" class="subnavigation">

	<?php

		
		include("./includes/navigation/staff/accountStatusButton.php");
		if($_SESSION["authenticated_tech"]=="true"){
			include("./includes/navigation/staff/accountStatusChangeButton.php");
			//include("/includes/navigation/staff/googleClassroomButton.php");
		}
		
			include("./includes/navigation/staff/googleGroupsButton.php");
			
		if($_SESSION["authenticated_tech"]=="true"){
			include("./includes/navigation/staff/newPasswordButton.php");
			include("./includes/navigation/staff/newUserButton.php");
			include("./includes/navigation/staff/sendEmailButton.php");
		}

	
	
	?>
	<span class="spacer"></span>
</div>

		</td>