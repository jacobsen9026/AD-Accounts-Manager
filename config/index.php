<?php
global $appConfig;
//debug($_GET["config"]);
if(isset($_GET["config"])){
    $config=$_GET["config"];
}else{
    $config="webApplicationSettings";
}



$views = getViews("./config");
foreach($views as $view){
    $viewFile = getViewFiles("./config/views/".$view)[0];
    $viewVariable = explode(".",$viewFile)[0];
    $search[]="&config=".$viewVariable;
    if($viewVariable==$config){
        $currentView=$view;
        //echo $currentView;
    }
}
//var_export($views);
debug($views);
?>


<form action="<?php echo $pageURL;?>" method="post" enctype="multipart/form-data">
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
        include("./config/configController.php");
        if(file_exists("./config/views/".$currentView."/".$config.".php")){
            debug("File Exists");

            include("./config/views/".$currentView."/".$config.".php");
        ?>
        <tr>
            <td>
                <button type="submit">Update Settings</button><br/>
            </td>
        </tr>

        <?php
        }
        ?>
        </form>
    <?php
    if(isset($_GET["advancedConfig"])){
    ?>

    <tr>
        <td>
            <br/>
            <a href="/">
                <button type="button">Go Back</button>
            </a>
        </td>
    </tr>
    <?php
    }
    ?>
    </table>
</form>
