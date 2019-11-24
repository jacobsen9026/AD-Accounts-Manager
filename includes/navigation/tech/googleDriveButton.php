<?php

	if ($_SESSION["authenticated_tech"]=="true") {
	?>
	
		<div>
			<a href="/?goto=/tech/google-drive/index.php">
				<button<?php
				if(strpos($_SERVER['REQUEST_URI'],"/tech/google-drive/")!== false){
					echo " class='currentPageButtonHighlight'";
				}
				?>>
				Google Drive
				</button>
			</a>
		</div>
	
	<?php
	}
	?>