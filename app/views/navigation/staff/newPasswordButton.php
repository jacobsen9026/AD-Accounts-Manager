<div>
    <a href="/?goto=/staff/password-reset/index.php"><button<?php
                                                             if(strpos($_SERVER['REQUEST_URI'],"/staff/password-reset/")!== false){
                                                                 echo " class='currentPageButtonHighlight'";
                                                             }
                                                             ?>>
        New Password
        </button>
    </a>
</div>