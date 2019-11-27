<?php
chdir("../");

require('./app/models/App.php');
require('./app/models/App/Config.php');


$app = new App();

$app->run();



/*



chdir("../");
//Show header
include("./app/header.php");


//Show content
include("./app/viewController.php");


//Show footer
include("./app/footer.php");

//debug("Footer");
//debug(get_included_files());





*/






?>