<div  class="navigationButton">
    <?php //if(isset($_SESSION['authenticated_admin'])){
    //if($_SESSION['authenticated_admin']=="true"){
    ?>
    <button id="parentButton" <?php
	/*
        if($_SESSION["authenticated_admin"]!="true"){
            echo "style='opacity:0.0;'";
            if(strpos($_SERVER['REQUEST_URI'],"/parents/")!== false){
                echo "background-color:#084d70'";
            }
        }elseif(strpos($_SERVER['REQUEST_URI'],"/parents/")!== false){
            echo "class='currentPageButtonHighlight'";
        }
		*/
            ?> onclick="showParentDropdown()">
        Parents
    </button>

    <div id="parentDropdown" class="subnavigation">
        <?php
		/*
        if ($_SESSION["authenticated_admin"]=="true") {
            include("./app/views/navigation/parents/googleGroupsCheckButton.php");
            include("./app/views/navigation/parents/googleGroupsManagerButton.php");
        }
		**/
        ?>

    </div>
    <?php
    //}
//}
    ?>
</div>