<?php

use App\Api\WindowsRM;

$computer = $this->computer;
$win = new WindowsRM();
$workstationReachable = $win->testConnection($computer);

if ($workstationReachable) {
    echo "$computer is reachable";
    echo "<br>" . $win->DNSLookup($computer);
} else {
    echo "$computer is not reachable";
}