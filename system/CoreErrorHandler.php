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

namespace system;

/**
 * Description of ErrorHandler
 *
 * @author cjacobsen
 */
use system\Core;
use system\SystemLogger;
use Error;

class CoreErrorHandler {

    public static $instance;

    function __construct() {
        set_error_handler(array($this, 'handleError'));
        //set_exception_handler(array($this, 'handleException'));
        if (isset(self::$instance)) {
            return self::$instance;
        } else {
            self::$instance = $this;
        }
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

    //put your code here

    public function handleError($code, $description = null, $file = null, $line = null, $context = null) {
        $output = "Error: [$code] $description";
        if ($file != null and $line != null) {
            $output = "Error: $file:$line [$code] $description";
        }
        SystemLogger::get()->error($output);
    }

    /* @var $exception Error */

    function handleException($exception) {
        /* @var $file string */
        ob_flush();
        $file = $exception->getFile();
        //echo "<br/><br/><br/><br/>";

        if (isset($exception->xdebug_message)) {
            $xdebugMessage = $exception->xdebug_message;
            SystemLogger::get()->error($xdebugMessage);
        }
        /* @var $lineNumber int */
        $lineNumber = $exception->getLine();

        /* @var $message string */
        $message = $exception->getMessage();

        /* @var $trace array */
        $trace = $exception->getTrace();
        $line = file($file)[$lineNumber];
        //var_dump($exception);
        //var_dump(file($file));
        //Log the exception to our server's error logs.
        //SystemLogger::get()->error($exception);
        //Send a 500 internal server error to the browser.
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        //Display our custom error page.
        //var_dump($exception);
        $logMessage = $file . ':' . $lineNumber . ' ' . $message;
        $errorMessage = '<div class="alert alert-danger">Fatal Errror <br/><br/>' . $message . '<br/><br/>' . $file
                . ':' . $lineNumber . '<br/><pre>'
                . $line . '</pre></div>';
        //Kill the script.
        echo $errorMessage;
        Core::get()->abort($logMessage);
    }

}
