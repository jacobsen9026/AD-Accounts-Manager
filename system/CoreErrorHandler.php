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

namespace System;

/**
 * Description of CoreErrorHandler
 *
 * This is the custom core error/exception handler class to catch all error that may occur.
 *
 * @author cjacobsen
 */

use System\Core;
use System\SystemLogger;
use Error;

class CoreErrorHandler
{

    public static $instance;

    /**
     * Set PHP Error and Exception handlers to functions in this object
     *
     * Returns a static instance of this object
     *
     * @return self
     */
    function __construct()
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);

        if (isset(self::$instance)) {
            return;
        } else {
            self::$instance = $this;
        }
    }

    /**
     *
     * @return type
     */
    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Custom Error Handler
     *
     * @param type $code
     * @param type $description
     * @param type $file
     * @param type $line
     * @param type $context
     */
    public function handleError($code, $description = null, $file = null, $line = null, $context = null)
    {
        $output = "Error: [$code] $description";
        if ($file != null and $line != null) {
            $output = "Error: $file:$line [$code] $description";
        }
        SystemLogger::get()->error($output);
    }

    /**
     * Custom Exception Handler
     *
     * @param Error $exception
     */
    function handleException($exception)
    {
        /* @var $file string */
        ob_flush();
        $file = $exception->getFile();

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
        //var_dump($trace);
        $traceOutput = '';
        $count = count($trace);
        for ($x = 1; $x < $count; $x++) {
            $traceOutput .= "<div>" . $trace[$x]["file"] . ":" . $trace[$x]["line"] . "</div>";

        }

        $surroundingCode = $lineNumber - 3 . file($file)[$lineNumber - 4];
        $surroundingCode .= $lineNumber - 2 . file($file)[$lineNumber - 3];
        $surroundingCode .= $lineNumber - 1 . file($file)[$lineNumber - 2];
        $surroundingCode .= $lineNumber . file($file)[$lineNumber - 1];
        $surroundingCode .= $lineNumber + 1 . file($file)[$lineNumber - 0];
        $surroundingCode .= $lineNumber + 2 . file($file)[$lineNumber + 1];
        $surroundingCode .= $lineNumber + 3 . file($file)[$lineNumber + 2];
        $surroundingCode = str_replace("'", '"', $surroundingCode);

        $logMessage = $file . ':' . $lineNumber . ' ' . $message;
        if (Core::get()->inDebugMode()) {
            //Send a 500 internal server error to the browser.
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

            $errorMessage = '<div class="m-5 p-5 alert alert-danger"><h4>Fatal Error</h4>' . $message . '<br/><br/>' . $file
                . ':' . $lineNumber . '<br/><pre>'
                . $surroundingCode . '</pre>'
                . $traceOutput
                . '</div>';
            echo $errorMessage;
        }
        /*
         * Kill the core execution.
         */
        Core::get()->abort($logMessage);
    }

}
