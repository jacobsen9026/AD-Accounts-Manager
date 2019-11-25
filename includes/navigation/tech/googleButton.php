<?php

if ($_SESSION["authenticated_tech"]=="true") {
?>

<div>
    <a href="/?goto=/tech/google/index.php">
        <button<?php
    if(strpos($_SERVER['REQUEST_URI'],"/tech/google/ind")!== false){
        echo " class='currentPageButtonHighlight'";
    }
                ?>>
            Google
        </button>
    </a>
</div>

<?php
}
?>