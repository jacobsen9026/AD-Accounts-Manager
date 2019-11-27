<?php
chdir("../");
//Show header
include("./app/header.php");


//Show content
include("./app/viewController.php");


//Show footer
include("./app/footer.php");

//debug("Footer");
//debug(get_included_files());

?>