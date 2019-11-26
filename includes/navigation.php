<table id="navigation" class="navigation">
    <tr>
        <?php
        $numberOfButtons=2;

        include("./includes/navigation/userButton.php");
        include("./includes/navigation/studentButton.php");
        /*
        if(isset($_SESSION['authenticated_admin'])){
            if($_SESSION['authenticated_admin']=="true"){
                include("./includes/navigation/staffButton.php");
                $numberOfButtons++;
                include("./includes/navigation/parentButton.php");
                $numberOfButtons++;
            }
        }
		*/
        include("./includes/navigation/staffButton.php");
        include("./includes/navigation/parentButton.php");
        /*
        if(isset($_SESSION['authenticated_tech'])){
            if($_SESSION["authenticated_tech"]=="true"){
                include("./includes/navigation/techButton.php");
                $numberOfButtons++;
            }
        }
		*/
        include("./includes/navigation/techButton.php");
        ?>
    </tr>
</table>





<?php
/*
switch ($numberOfButtons){
    case 2:
        echo "<style>
	.navigation td, .subnavigation{
		    width: 50%;
	}
</style>";
        break;
    case 3:
        echo "<style>
	.navigation td, .subnavigation{
		    width: 33%;
	}
</style>";
        break;
    case 4:
        echo "<style>
	.navigation td, .subnavigation{
		    width: 25%;
	}
</style>";
        break;
    case 5:
        echo "<style>
	.navigation td, .subnavigation{
		    width: 20%;
	}
</style>";
        break;



}
*/
?>













