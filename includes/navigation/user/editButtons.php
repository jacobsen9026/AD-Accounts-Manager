<?php
	if ($_SESSION["authenticated_tech"]=="true"){
		if(strpos($_SERVER['REQUEST_URI'],"/editor/index.php")== false) {
	?>
		
		
		<div>
			<div style="width:50%;display:inline;">
				<a href="/?goto=/editor/index.php&page=<?php echo str_replace("/?goto=","",$pageURL);?>" target="_blank">
					<button style="width:48%;">
					This Page
					</button>
				</a>
			</div>
			<div style="width:50%;display:inline;">
			<a href="/?goto=/editor/index.php&page=/header.php" target="_blank">
				<button style="width:48%;">
				Header
				</button>
			</a>
			</div>
		</div>
		<?php
		}else{
			?>
			<div>
			
			<div style="width:100%;display:inline;">
			<a href="/?goto=/editor/index.php&page=/header.php" target="_blank">
				<button>
				Header
				</button>
			</a>
			</div>
		</div>
			<?php
		}
		?>
		<div>
			<div style="width:50%;display:inline;">
			<a href="/?goto=/editor/index.php&page=/style/style.css" target="_blank">
				<button style="width:48%;">
				Style
				</button>
			</a>
			</div>
			<div style="width:50%;display:inline;">
			<a href="/?goto=/editor/index.php&page=/style/mobilestyle.css" target="_blank">
				<button style="width:48%;">
				Mobile Style
				</button>
			</a>
			</div>
		</div>
			<div>
			<div style="width:50%;display:inline;">
			<a href="/?goto=/editor/index.php&page=/style/lightTheme.css" target="_blank">
				<button style="width:48%;">
				Light Theme
				</button>
			</a>
			</div>
			<div style="width:50%;display:inline;">
			<a href="/?goto=/editor/index.php&page=/style/darkTheme.css" target="_blank">
				<button style="width:48%;">
				Dark Theme
				</button>
			</a>
			</div>
		</div>
		<br/>
	
	
	
	<?php
	}
	
	?>