<div  class="navigationButton">
    <button id="studentButton" <?php
	/*
            if(!isset($_SESSION['authenticated_basic'])){
                $_SESSION['authenticated_basic']=false;
            }
            if($_SESSION["authenticated_basic"]!="true"){
                echo "style='visibility:hidden;'";
                if(strpos($_SERVER['REQUEST_URI'],"/students/")!== false){
                    echo "background-color:#084d70'";
                }
            }elseif(strpos($_SERVER['REQUEST_URI'],"/students/")!== false){
                echo "class='currentPageButtonHighlight'";
            }
			*/
            ?> onclick="showStudentDropdown()">
        Students 
    </button>

    <div id="studentDropdown" class="subnavigation">

        <?php

/*
        include("./app/views/navigation/students/accountStatusButton.php");
        include("./app/views/navigation/students/accountStatusChangeButton.php");
        include("./app/views/navigation/students/googleClassroomButton.php");
        include("./app/views/navigation/students/googleGroupsButton.php");
        include("./app/views/navigation/students/hdriveButton.php");
        include("./app/views/navigation/students/newPasswordButton.php");
        include("./app/views/navigation/students/newUserButton.php");
		*/
        ?>
        <span class="spacer"></span>
    </div>
</div>