<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app;

/**
 * Description of CoreCookie
 *
 * @author cjacobsen
 */
class CoreCookie {

    //put your code here
    private $config;

    function __construct($config) {
        $this->config = $config;
    }

    public function set($key, $value) {
        if (setcookie($key, $value)) {
            return true;
        }
        return false;
    }

    public function get($key) {
        return $_COOKIE[$key];
    }

}
