<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system;

/**
 * Description of CoreException
 *
 * @author cjacobsen
 */
class CoreException extends \Exception {

    protected $message = 'Unknown exception';   // exception message
    private $string;                          // __toString cache
    protected $code = 0;                        // user defined exception code
    protected $file;                            // source filename of exception
    protected $line;                            // source line of exception
    private $trace;                           // backtrace
    private $previous;                        // previous exception if nested exception

    //
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null) {
        // some code
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
        $that = $this;
        set_error_handler(function() use ($that) {
            $that->handleError();
        });
    }

    public function handleError($errno, $errstr, $errfile, $errline) {
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting, so let it fall
            // through to the standard PHP error handler
            return false;
        }

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
        CoreLogger::get()->error($message);
        /* Don't execute PHP internal error handler */
        return true;
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function customFunction() {
        echo "A custom function for this type of exception\n";
    }

}

?>
