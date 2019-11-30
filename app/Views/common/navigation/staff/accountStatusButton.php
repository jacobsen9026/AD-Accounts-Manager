<div>
    <a href="/?goto=/staff/account-status/index.php">
        <button<?php
                if(strpos($_SERVER['REQUEST_URI'],"/staff/account-status/")!== false){
                    echo " class='currentPageButtonHighlight'";
                }
                ?>>
            Account Status
        </button>
    </a>
</div>