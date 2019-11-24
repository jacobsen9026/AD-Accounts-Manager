<?php

	if ($_SESSION["authenticated_tech"]=="true") {
	?>
	
		<div>
			<a href="/?goto=/tech/computers/index.php">
				<button<?php
				if(strpos($_SERVER['REQUEST_URI'],"/tech/computers/")!== false){
					echo " class='currentPageButtonHighlight'";
				}
				?>>
				Computers
				</button>
			</a>
		</div>
	
	<?php
	}
	?>