<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app;

/**
 * Description of CoreLogger
 *
 * @author cjacobsen
 */
use system\Parser;

class CoreLogger extends Parser {

    private $debugLog;
    private $errorLog;
    private $infoLog;
    private $warningLog;

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
        if (is_array($message)) {
            $message = var_export($message, true);
        }
        if (is_object($message)) {
            $message = $this->debugObject($message);
        }
        $message = str_replace("\n", "", $message);
        $caller = backTrace();
        $caller["file"] = $this->sanitize($caller['file']);
        return $caller["file"] . ":" . $caller["line"] . ' ' . $message;
    }

    /**
     *
     * @param type $message
     */
    public function debug($message) {
        $this->debugLog[] = $this->preProcessMessage($message);
    }

    /**
     *
     * @param type $message
     */
    public function warning($message) {
        $this->warningLog[] = $this->preProcessMessage($message);
    }

    /**
     *
     * @param type $message
     */
    public function error($message) {
        $this->errorLog[] = $this->preProcessMessage($message);
    }

    /**
     *
     * @param type $message
     */
    public function info($message) {
        $this->infoLog[] = $this->preProcessMessage($message);
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
