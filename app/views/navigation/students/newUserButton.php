<?php

if(isset($_SESSION["authenticated_tech"])){
    if ($_SESSION["authenticated_tech"]=="true") {
?>

<div>



    <a href="/?goto=/students/create-user/index.php">
        <button<?php
        if(strpos($_SERVER['REQUEST_URI'],"/students/create-user/")!== false){
            echo " class='currentPageButtonHighlight'";
        }
                ?>>
            New User
        </button>
    </a>

</div>

<?php
    }
}
?>