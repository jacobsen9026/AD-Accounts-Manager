	<?php
	if ($_SESSION["authenticated_tech"]=="true") {
	?>
		<div>
			<a href="/?goto=/staff/create-user/index.php">
				<button<?php
				if(strpos($_SERVER['REQUEST_URI'],"/staff/create-user/")!== false){
					echo " class='currentPageButtonHighlight'";
				}
				?>>
				New User
				</button>
			</a>
		</div>
	
	<?php
	
}
?>