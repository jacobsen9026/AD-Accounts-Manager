<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system;

/**
 * Description of ErrorHandler
 *
 * @author cjacobsen
 */
use system\Core;
use system\SystemLogger;

class CoreErrorHandler {

    public static $instance;

    function __construct() {
        set_error_handler(array($this, 'handleError'));
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

    public function handleError($code, $description, $file = null, $line = null, $context = null) {
        $output = "Error: [$code] $description";
        if ($file != null and $line != null) {
            $output = "Error: $file:$line [$code] $description";
        }
        SystemLogger::get()->error($output);
    }

}