<?php
if (isset($_POST["path"])){
    if(createNewPage($_POST["path"])){
?>
<script>

    location.reload();
</script>


<?php

    }

}
$randomNumber=rand(1,8);
?>
<table id="container">
    <tr>
        <th>
            <img src="/img/sad-face.png" style="width:2em;"/>Sorry<img src="/img/sad-face.png" style="width:2em;"/>
        </th>
    </tr>
    <tr>
        <td style="text-align:center">
            <div  class="centered"  style="height:275px;width:375px;vertical-align:middle;overflow:hidden">
                <img style="width:100%;display:inline-block;" src="/img/error/error<?php echo $randomNumber;?>.gif"/>
            </div>
            <br/><br/>
            Couldn't find the page you were looking for.
            <br/>
            <?php
            if($_SESSION["authenticated_tech"]=="true"){
            ?>
            <br/>
            <form action="<?php echo $pageURL;?>" method="post">
                <?php
                if (isset($_GET["goto"])){
                ?>
                <input name="path" value="<?php echo str_replace("/?goto=","",$pageURL);?>" hidden />	
                <?php
                }else{
                ?>
                <input name="path" value="<?php echo $pageURL;?>" hidden />	
                <?php
                }
                ?>
                <button type="submit"><big>Create New Page</big> <br/><br/><small>"<?php echo str_replace("/?goto=","",$pageURL);?>"</small><br/><br/></button>



            </form>

            <?php	
            }
            ?>
            <br/>
            <button type="button" onclick="window.history.back();" >Back</button>
            <br/>
            <br/>
            <a href="/">
                <button type="button" value="Back to Home">Back to Home</button>

            </a>
            <br/><br/>
        </td>
    </tr>
</table>
