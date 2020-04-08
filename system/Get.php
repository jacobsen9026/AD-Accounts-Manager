<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system;

/**
 * Description of Get
 *
 * Represents the Get Variable of the Request
 *
 * @author cjacobsen
 */
abstract class Get {

    /**
     * Check if the GET was used in the request
     * @return boolean
     */
    public static function isSet() {
        if (isset($_GET) and $_GET != null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the contents of the GET array, or false if GET was not used.
     * @return null|array
     */
    public static function getAll() {
        if (Get::isSet()) {
            return $_GET;
        } else {
            return null;
        }
    }

}
