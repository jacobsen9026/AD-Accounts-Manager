<td  class="navigationButton">
			<?php if(isset($_SESSION['authenticated_tech'])){
            if($_SESSION['authenticated_tech']=="true"){
				?>
			<button id="techButton" <?php
			if($_SESSION["authenticated_tech"]!="true"){
				echo "style='visibility:hidden;'";
				if(strpos($_SERVER['REQUEST_URI'],"/tech/")!== false){
				echo "background-color:#084d70'";
				}
			}elseif(strpos($_SERVER['REQUEST_URI'],"/tech/")!== false){
				echo "class='currentPageButtonHighlight'";
			}
				?> onclick="showTechDropdown()">
				Tech
			</button>

<?php 
	if ($_SESSION["authenticated_tech"]=="true") {
			?>
	
			<div id="techDropdown" class="subnavigation">
				<?php
					
		
					include("./includes/navigation/tech/googleButton.php");
					include("./includes/navigation/tech/googleDriveButton.php");
					include("./includes/navigation/tech/computersButton.php");
					
			?>
				
			</div>
			
			
			<?php
					
	}else{
			?>
			<div id="techDropdown" class="subnavigation">
				</div>
			
			
			
			<?php
}

			}
			}
			?>
		</td>