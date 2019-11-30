
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
        <?php
		echo $this->renderPartial('/common/headers/meta');
		echo $this->renderPartial('/common/headers/css');
		echo $this->renderPartial('/common/headers/js');
		//$this->renderSection('/partials/meta');
				
				?>
    </head>
	<body <?php //if (isset($_SESSION["authenticated_basic"]) && $_SESSION["authenticated_basic"]=="true"){ ?> onload="onLoad();startSessionTimeoutTimer();"<?php //} ?> >
<div id="wrapper" class=''>	
	<?php 
    echo $this->renderPartial('/common/header');
    echo $this->renderPartial('/common/navigation');
    echo $this->renderPartial('/common/overlays/pageLoader.php');
	?>
	<div class="appContainer">
	<?php
	$this->renderSection('content');
	?>
	</div>
 

        <?php
        //if($appConfig["installComplete"] and !isset($grab)){
            //Load the top menu navigation
        //    include("./app/includes/navigation.php");
        //}
        ?>


       
      </div>  
</body>

</html>



