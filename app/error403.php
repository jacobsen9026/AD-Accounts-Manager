<?php
if (isset($_POST["path"])){
    if(createNewPage(str_replace("/?goto=","",$pageURL))){
?>
<script>

    location.reload();
</script>


<?php
    }


}
$randomNumber=rand(1,5);
?>
<table id="container">
    <tr>
        <th>
            FORBIDDEN
        </th>
    </tr>
    <tr>
        <td style="text-align:center">
            <div style="margin-left:auto;margin-right:auto;height:275px;width:275px;vertical-align:middle;">
                <img style="width:100%;display:inline-block;" src="/img/error/403.gif"/>
            </div>
            <br/><br/>
            May the rath of Judy reign upon you!
            <br/>
            <?php
            if($_SESSION["authenticated_tech"]=="true"){
            ?>


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
