<div>
    <a href="/?goto=/staff/send-welcome-email/index.php">
        <button<?php
                if(strpos($_SERVER['REQUEST_URI'],"/staff/send-welcome-email/")!== false){
                    echo " class='currentPageButtonHighlight'";
                }
                ?>>Send Email
        </button>
    </a>
</div>

		