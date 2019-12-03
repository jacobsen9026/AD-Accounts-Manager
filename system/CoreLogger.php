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
class CoreLogger extends Parser {

    private $debugLog;
    private $errorLog;
    private $infoLog;
    private $warningLog;
    public static $instance;

    function __construct() {
        self::$instance = $this;
    }

    /**
     *
     * @return type
     */
    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     *
     * @return type
     */
    public function getLogs() {
        return array('debug' => $this->debugLog, 'error' => $this->errorLog, 'warning' => $this->warningLog, 'info' => $this->infoLog);
    }

    /**
     *
     * @param type $message
     * @return type
     */
    private function preProcessMessage($message) {
        if (is_object($message)) {
            $message = $this->debugObject($message);
        }
        return str_replace("\n", "", $message);
    }

    /**
     *
     * @param type $message
     */
    public function debug($message) {
        $this->preProcessMessage($message);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);
        $caller["file"] = $this->sanitize($caller['file']);
        $logMessage = $caller["file"] . ":" . $caller["line"] . ' ' . $message;
        $this->debugLog[] = $logMessage;
    }

    /**
     *
     * @param type $message
     */
    public function warning($message) {
        $this->preProcessMessage($message);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);
        $caller["file"] = $this->sanitize($caller['file']);
        $logMessage = $caller["file"] . ":" . $caller["line"] . ' ' . $message;
        $this->warningLog[] = $logMessage;
    }

    /**
     *
     * @param type $message
     */
    public function error($message) {
        $this->preProcessMessage($message);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);

        $caller["file"] = $this->sanitize($caller['file']);
        $logMessage = $caller["file"] . ":" . $caller["line"] . ' ' . $message;
        $this->errorLog[] = $logMessage;
    }

    /**
     *
     * @param type $message
     */
    public function info($message) {
        $this->preProcessMessage($message);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);

        $caller["file"] = $this->sanitize($caller['file']);
        $logMessage = $caller["file"] . ":" . $caller["line"] . ' ' . $message;
        $this->infoLog[] = $logMessage;
    }

    /**
     *
     * @param type $array
     * @return string
     */
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

    /**
     *
     * @param type $object
     * @return type
     */
    public function debugObject($object) {
        return htmlspecialchars(print_r($object, true));
    }

}
