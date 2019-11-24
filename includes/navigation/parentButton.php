<td  class="navigationButton">
		
			<button id="parentButton" <?php
			if($_SESSION["authenticated_admin"]!="true"){
				echo "style='opacity:0.0;'";
				if(strpos($_SERVER['REQUEST_URI'],"/parents/")!== false){
				echo "background-color:#084d70'";
				}
			}elseif(strpos($_SERVER['REQUEST_URI'],"/parents/")!== false){
				echo "class='currentPageButtonHighlight'";
			}
				?> onclick="showParentDropdown()">
				Parents
			</button>

<div id="parentDropdown" class="subnavigation">
<span class="spacer"></span>
	<?php
	if ($_SESSION["authenticated_admin"]=="true") {
		include("./includes/navigation/parents/googleGroupsCheckButton.php");
		include("./includes/navigation/parents/googleGroupsManagerButton.php");
	}
	?>
	
</div>
		</td>