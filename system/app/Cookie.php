<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app;

/**
 * Description of Cookie
 *
 * @author nbranco
 */
abstract class Cookie {

    /**
     *
     * @param type $cookieName The Name of the cookie
     * @return boolean | mixed Returns false if cookie does not exist
     */
    public static function get($name) {

        if (!is_null($_COOKIE)) {
            if (key_exists($name, $_COOKIE)) {
                return $_COOKIE[$name];
            }
        }
        return false;
    }

    /**
     *
     * @param type $name  The Name of the Cookie
     * @param type $value  The Value of the Cookie
     * @param type $expires  The length of time until the expires, measured in seconds.  If not used, the cookie will be set to expire in 100 years
     */
    public static function set($name, $value = "", $expires = null, $path = "/") {

        if (is_null($expires)) {
            $expires = 100 * 365 * 24 * 60 * 60;
        }
        setcookie($name, $value, time() + $expires, $path);
    }

}
