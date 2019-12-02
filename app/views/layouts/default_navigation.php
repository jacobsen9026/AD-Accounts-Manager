<table id="navigation" class="navigation">
    <tr>
        <?php echo $this->view("navigation/userButton"); ?>
        <?php echo $this->view("navigation/studentButton"); ?>
        <?php echo $this->view("navigation/staffButton"); ?>
        <?php echo $this->view("navigation/parentButton"); ?>
        <?php echo $this->view("navigation/techButton"); ?>


        <?php
        $numberOfButtons = 2;

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













