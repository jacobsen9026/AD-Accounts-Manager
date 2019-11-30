<?php

if($_SESSION["authenticated_basic"]=="true"){
?>


<div>
    <a href="/?goto=/students/password-reset/index.php"><button<?php
    if(strpos($_SERVER['REQUEST_URI'],"/students/password-reset/")!== false){
        echo " class='currentPageButtonHighlight'";
    }
                                                                ?>>
        New Password
        </button>
    </a>
</div>


<?php
}
?>