<?php
if(strpos($pageURL,"/editor")==false){
    if(isset($goto)){
        if ($goto != "" && $goto!="/"){
            if(file_exists("./help".$goto)){
?>

<a href='/?goto=/help<?php echo str_replace("/?goto=","",$pageURL);?>'>
    <div onmouseover="hoverOverHelpButton(this);" onmouseleave="revertHelpButton(this);" class="floatingHelpButton">

        <img src="/img/help.png"/>

    </div>
</a>
<?php
                                           }
        }
    }
}
?>