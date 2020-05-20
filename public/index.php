<?php

namespace System;

chdir("../");
require('./system/Core.php');
$core = new Core();

$core->run();
?>