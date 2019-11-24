	<?php
	if (isset($_SESSION['authenticated_basic'])){
		if ($_SESSION["authenticated_basic"]=="true") {
		?>

	<div>
				<a href="/?goto=/logout/index.php">
					<button>
						Logout
					</button>
				</a>
			</div>
			
				<?php
		}
	}
	?>