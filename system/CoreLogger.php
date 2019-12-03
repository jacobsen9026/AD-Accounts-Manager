<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system;

/**
 * Description of CoreLogger
 *
 * @author cjacobsen
 */
class CoreLogger {

    private $debugLog;
    private $errorLog;
    private $infoLog;
    private $warningLog;
    public static $instance;

    function __construct() {
        self::$instance = $this;
    }

    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getLogs() {
        return array('debug' => $this->debugLog, 'error' => $this->errorLog, 'warning' => $this->warningLog, 'info' => $this->infoLog);
    }

    public function debug($message) {
        $message = str_replace("\n", "", $message);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);
        $caller["file"] = str_replace("\\", "/", $caller['file']);
        $logMessage = $caller["file"] . ":" . $caller["line"] . ' ' . $message;
        $this->debugLog[] = $logMessage;
    }

    public function warning($message) {
        $message = str_replace("\n", "", $message);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);
        $logMessage = $caller["file"] . ":" . $caller["line"] . ' ' . $message;
        $this->warningLog[] = $logMessage;
    }

    public function error($message) {
        $message = str_replace("\n", "", $message);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);
        $logMessage = $caller["file"] . ":" . $caller["line"] . ' ' . $message;
        $this->errorLog[] = $logMessage;
    }

    public function info($message) {

        $message = str_replace("\n", "", $message);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);
        $logMessage = $caller["file"] . ":" . $caller["line"] . ' ' . $message;
        $this->infoLog[] = $logMessage;
    }

//put your code here
    public function debugArray($array) {
        if (isset($array)) {
            $message = "<div>";
            foreach ($array as $name => $option) {
                if (is_array($option)) {
                    $message = $message . "<strong>" . $name . "</strong><br/>";
                    foreach ($option as $name => $option2) {

                        $message = $message . $name . ": " . var_export($option2, true) . "<br/>";
                    }
                } else {
                    $message = $message . "<strong>" . $name . "</strong><br/>" . var_export($option, true) . "<br/>";
                }
                $message = $message . "<br/>";
            }
            $message = $message . "</div>";
            return $message;
        }
    }

}
