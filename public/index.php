<?php

namespace system;

chdir("../");
require('./system/Core.php');
$core = new Core();

$core->run();
?>