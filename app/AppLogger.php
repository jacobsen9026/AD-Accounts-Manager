<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app;

/**
 * Description of AppLogger
 *
 * @author cjacobsen
 */
use system\app\CoreLogger;

class AppLogger extends CoreLogger {

    public static $instance;

    function __construct() {

        self::$instance = $this;
        ;
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

}
