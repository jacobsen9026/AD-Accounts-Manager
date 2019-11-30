	<?php
	if (isset($_SESSION['authenticated_basic'])){
		if ($_SESSION["authenticated_basic"]=="true" and (strpos($pageURL,"help/")==false) and $goto!="") {
		?>
		
			<div>
				<a href="/?goto=/help<?php echo str_replace("/?goto=","",$pageURL);?>">
					<button<?php
					if(strpos($_SERVER['REQUEST_URI'],"/help/$pageURL")!== false){
						echo " class='currentPageButtonHighlight'";
					}
					?>>
					Help
					</button>
				</a>
			</div>
		
		
		
		<?php
		}
	}
	?>