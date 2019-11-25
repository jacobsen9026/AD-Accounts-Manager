<div class="configNavContainer">
<?php
$search=Array("&config=webAdminSettings","config=webApplicationSettings",
"&config=districtSettings","&config=googleSettings","&config=emailSettings");

?>

        <div class="configNavButton">
            <a href="<?php echo str_replace($search,"",$pageURL);?>&config=webApplicationSettings">
                <button type="button" <?php if($config=='webApplicationSettings' or $config==""){echo 'class="currentPageButtonHighlight"';}?> >Web Application Settings</button>
            </a>
        </div>
        <div class="configNavButton">
            <a href="<?php echo str_replace($search,"",$pageURL);?>&config=webAdminSettings">
                <button type="button" <?php
                        if(isset($config)){
                            if($config=='webAdminSettings'){
                                echo 'class="currentPageButtonHighlight"';
                            }
                        }
                        ?> >Web Admin Settings</button>
            </a>
        </div>
        <div class="configNavButton">
            <a href="<?php echo str_replace($search,"",$pageURL);?>&config=districtSettings">
                <button type="button" <?php
                        if(isset($config)){
                            if($config=='districtSettings'){
                                echo 'class="currentPageButtonHighlight"';
                            }
                        }
                        ?> >District Settings</button>
            </a>
        </div>
        <div class="configNavButton">
            <a href="<?php echo str_replace($search,"",$pageURL);?>&config=googleSettings">
                <button type="button" <?php
                        if(isset($config)){
                            if($config=='googleSettings'){
                                echo 'class="currentPageButtonHighlight"';
                            }
                        }
                        ?> >Google Settings</button>
            </a>
        </div>
        <div class="configNavButton">
            <a href="<?php echo str_replace($search,"",$pageURL);?>&config=adSettings">
                <button type="button" <?php
                        if(isset($config)){
                            if($config=='adSettings'){
                                echo 'class="currentPageButtonHighlight"';
                            }
                        }
                        ?> >Active Directory</button>
            </a>
        </div>
        <div class="configNavButton">
            <a href="<?php echo str_replace($search,"",$pageURL);?>&config=emailSettings">
                <button type="button" <?php
                        if(isset($config)){
                            if($config=='emailSettings'){
                                echo 'class="currentPageButtonHighlight"';
                            }
                        }
                        ?> >Email Settings</button>
            </a>
        </div>


</div>
<br/><br/>