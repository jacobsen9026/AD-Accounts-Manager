<div id="navigation" class="navigation">
    
        <?php
		
		echo $this->renderPartial('/common/navigation/user');
		
		echo $this->renderPartial('/common/navigation/student');
		echo $this->renderPartial('/common/navigation/staff');
		echo $this->renderPartial('/common/navigation/parent');
		echo $this->renderPartial('/common/navigation/tech');
		
		
		
		
		
		
		
		
        $numberOfButtons=2;

        //include("./app/views/navigation/userButton.php");
        //include("./app/views/navigation/studentButton.php");
        /*
        if(isset($_SESSION['authenticated_admin'])){
            if($_SESSION['authenticated_admin']=="true"){
                include("./app/views/navigation/staffButton.php");
                $numberOfButtons++;
                include("./app/views/navigation/parentButton.php");
                $numberOfButtons++;
            }
        }
		*/
        //include("./app/views/navigation/staffButton.php");
        //include("./app/views/navigation/parentButton.php");
        /*
        if(isset($_SESSION['authenticated_tech'])){
            if($_SESSION["authenticated_tech"]=="true"){
                include("./app/views/navigation/techButton.php");
                $numberOfButtons++;
            }
        }
		*/
        //include("./app/views/navigation/techButton.php");
        ?>
  
</div>
















