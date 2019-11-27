	<?php
	if (isset($_SESSION['authenticated_tech'])){
		if ($_SESSION["authenticated_tech"]=="true") {
		?>
		
			<div>
				<a href="/?goto=/config/index.php">
					<button<?php
					if(strpos($_SERVER['REQUEST_URI'],"/config/index.php")!== false){
						echo " class='currentPageButtonHighlight'";
					}
					?>>
					Settings
					</button>
				</a>
			</div>
		
		
		
		<?php
		}
	}
	?>