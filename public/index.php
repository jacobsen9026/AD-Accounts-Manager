<?php

namespace system;

chdir("../");

require('./system/Core.php');
$app = new Core();

$app->run();
?>