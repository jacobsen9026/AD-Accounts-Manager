<?php

/*
 * The MIT License
 *
 * Copyright 2019 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace system\common;

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
    private $startTime;
    private $log;

    function __construct() {
        $this->startTime = floatval(microtime());
    }

    /**
     *
     * @return type
     */
    public function getLogs() {
        //var_dump($this->log);
        return array('debug' => $this->debugLog, 'error' => $this->errorLog, 'warning' => $this->warningLog, 'info' => $this->infoLog);
    }

    public function getLog() {
        //var_dump($this->log);
        return $this->log;
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
        $this->log[] = $this->packageMessage('debug', $message);
    }

    /**
     *
     * @param type $message
     */
    public function warning($message) {
        $this->warningLog[] = $this->preProcessMessage($message);
        $this->log[] = $this->packageMessage('warning', $message);
    }

    /**
     *
     * @param type $message
     */
    public function error($message) {
        $this->errorLog[] = $this->preProcessMessage($message);
        $this->log[] = $this->packageMessage('error', $message);
    }

    /**
     *
     * @param type $message
     */
    public function info($message) {
        $this->infoLog[] = $this->preProcessMessage($message);
        $this->log[] = $this->packageMessage('info', $message);
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

    private function packageMessage($level, $message) {
        $et = floatval(microtime()) - $this->startTime;
        return array($et, $level, $this->preProcessMessage($message));
    }

}
