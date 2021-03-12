<?php

/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
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
 * Description of CoreException
 *
 * @author cjacobsen
 */
class CoreException extends \Exception
{

    const APP_MISSING = 1;
    const APP_MISSING_RUN = 2;
    const MALFORMED_QUERY = 310;

    protected $message = 'Unknown exception';   // exception message
    protected $code = 0;                          // __toString cache
    protected $file;                        // user defined exception code
    protected $line;                            // source filename of exception

    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $that = $this;
        set_error_handler(function () use ($that) {
            $that->handleError($that->code, $that->message, $that->file, $that->line);
        });
    }

    /**
     *
     * @param type $errno
     * @param type $errstr
     * @param type $errfile
     * @param type $errline
     *
     * @return boolean
     */
    public function handleError($errno, $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting, so let it fall
            // through to the standard PHP error handler
            return false;
        }
        $message = '';
        switch ($errno) {
            case E_USER_ERROR:
                $message .= "<b>My ERROR</b> [$errno] $errstr<br />\n";
                $message .= "  Fatal error on line $errline in file $errfile";
                $message .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                $message .= "Aborting...<br />\n";
                break;

            case E_USER_WARNING:
                $message .= "<b>My WARNING</b> [$errno] $errstr<br />\n";
                break;

            case E_USER_NOTICE:
                $message .= "<b>My NOTICE</b> [$errno] $errstr<br />\n";
                break;

            default:
                $message .= "Unknown error type: [$errno] $errstr<br />\n";
                break;
        }
        SystemLogger::get()->error($message);
        /* Don't execute PHP internal error handler */
        return true;
    }


}

?>
