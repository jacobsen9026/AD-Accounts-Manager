<?php
	if ($_SESSION["authenticated_tech"]=="true") {
	?>
	
		<div>
			<a href="/?goto=/editor/index.php" target="_blank">
				<button<?php
				if(strpos($_SERVER['REQUEST_URI'],"/editor")!== false){
					echo " class='currentPageButtonHighlight'";
				}
				?>>
				Site Editor
				</button>
			</a>
		</div>
	
	
	
	<?php
	}
	?>