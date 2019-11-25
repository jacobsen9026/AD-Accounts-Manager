<?php
if ($_SESSION["authenticated_basic"]=="true") {
?>

<div>
    <a href="/?goto=/students/account-status/index.php"><button<?php
    if(strpos($_SERVER['REQUEST_URI'],"/students/account-status/")!== false){
        echo " class='currentPageButtonHighlight'";
    }
                                                                ?>>
        Account Status
        </button>
    </a>
</div>

<?php
}
?>