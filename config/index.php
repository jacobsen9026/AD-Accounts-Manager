<?php
global $appConfig;
//debug($_GET["config"]);
if(isset($_GET["config"])){
    $config=$_GET["config"];
}else{
    $config="webApplicationSettings";
}
?>

<table id="container">
    <tr>
        <td>
            <?php
            include("./config/includes/configNavBar.php");
            ?>
        </td>
    </tr>
    <?php
    debug($config);

    if(file_exists("./config/views/".$config.".php")){
        debug("File Exists");
        include("./config/views/".$config.".php");
    }
    ?>
</table>
