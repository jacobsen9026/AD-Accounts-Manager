<div class="configNavContainer">
    <?php
    foreach($views as $view){
        $thisView = getFolders("./config/views/".$view)[0];
		$viewVariable = "&config=".$thisView;
    ?>
    <div class="configNavButton">
        <a href="<?php echo str_replace($search,"",$pageURL);?><?php echo $viewVariable;?>">
            <button type="button" <?php if($config==$thisView or $config==""){echo 'class="currentPageButtonHighlight"';}?> ><?php echo $view;?></button>
        </a>
    </div>
    <?php
    }
    ?>


</div>
<br/><br/>