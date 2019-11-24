<?php
		if ($_SESSION["authenticated_basic"]=="true") {
	?>
	
	
		<div>
			<a href="/?goto=/students/classroom/index.php"><button<?php
			if(strpos($_SERVER['REQUEST_URI'],"/students/classroom/")!== false){
				echo " class='currentPageButtonHighlight'";
			}
			?>>
			Google Classroom
			</button>
			</a>
		</div>
	
	
	<?php
	}