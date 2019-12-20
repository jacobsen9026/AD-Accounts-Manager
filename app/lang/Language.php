<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\lang;

/**
 * Description of Language
 *
 * @author cjacobsen
 */
trait Language {

    //put your code here

    public static function get($name) {
        if (isset(Self::$strings[$name]) and Self::$strings[$name] != null) {
            return Self::$strings[$name];
        }
        return 'No language reference found for ' . $name;
    }

}
