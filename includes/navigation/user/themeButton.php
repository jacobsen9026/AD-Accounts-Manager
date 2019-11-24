<div>
			<form style="margin-bottom:0em;" action="<?php echo $pageURL;?>" method="post">
				
				<?php
					if(!isset($_COOKIE['theme'])){
						setcookie("theme","light",time() + (86400 * 3650),"/");
                        $_COOKIE["theme"]="light";
					}
					if($_COOKIE["theme"]=="dark"){
					?>
					<input style="display:none" name="theme" value="light" hidden>
					<button type="submit" >Switch To Light Theme</button>

					<?php
					}elseif($_COOKIE["theme"]=="light"){
						?>
					<input style="display:none" name="theme" value="dark" hidden>
					<button type="submit" >Switch To Dark Theme</button>
				
					<?php
					}else{
						?>
						<input style="display:none" name="theme" value="dark" hidden>
					<button type="submit" >Switch To Dark Theme</button>
					<?php
						
					}
					?>
			</form>
</div>