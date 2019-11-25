<div class="configNavContainer">
<?php


foreach($views as $view){
	$viewFile = getViewFiles("./config/views/".$view)[0];
	$viewVariable = explode(".",$viewFile)[0];
	$search[]="&config=".$viewVariable;
	
}



foreach($views as $view){
	$viewFile = getViewFiles("./config/views/".$view)[0];
	$viewVariable = explode(".",$viewFile)[0];
	//var_export($search);
	//echo "go";
	?>
	<div class="configNavButton">
            <a href="<?php echo str_replace($search,"",$pageURL);?>&config=<?php echo $viewVariable;?>">
                <button type="button" <?php if($config==$view or $config==""){echo 'class="currentPageButtonHighlight"';}?> ><?php echo $view;?></button>
            </a>
        </div>
		<?php
}





?>


</div>
<br/><br/>