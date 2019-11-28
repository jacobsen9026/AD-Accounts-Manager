
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

<script>
    onLoad();
</script>




</div>



<!--
Site Written By: Chris Jacobsen
With Credit to Codiad and IceCoder
Creation: September-October 2017
Updated: October 2019
-->

</body>

</html>


