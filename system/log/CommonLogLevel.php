<?php


namespace System\Log;


use Psr\Log\LogLevel;

class CommonLogLevel extends LogLevel
{

    const NUMERIC_VALUE = ["debug" => 7, "info" => 6, "notice" => 5, "warning" => 4, "error" => 3, "critical" => 2, "alert" => 1, "emergency" => 0];


}