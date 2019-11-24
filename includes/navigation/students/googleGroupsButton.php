<?php
	if($_SESSION["authenticated_basic"]=="true"){
	?>
	
	
		<div>
			<a href="/?goto=/students/google-groups/index.php">
				<button<?php
				if(strpos($_SERVER['REQUEST_URI'],"/students/google-groups/")!== false){
					echo " class='currentPageButtonHighlight'";
				}
				?>>
				Google Groups
				</button>
			</a>
		</div>
	<?php
	}
	?>