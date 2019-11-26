<div>
    <a href="/?goto=/staff/account-status-change/index.php">
        <button<?php
                if(strpos($_SERVER['REQUEST_URI'],"/staff/account-status-change/")!== false){
                    echo " class='currentPageButtonHighlight'";
                }
                ?>
                >
            Account
            <?php
            if($_SESSION["authenticated_tech"]=="true"){
                echo "Lock/";
            }?>
            Unlock
        </button>
    </a>
</div>