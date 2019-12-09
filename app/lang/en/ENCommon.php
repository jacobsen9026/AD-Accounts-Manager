<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\lang\en;

/**
 * Description of Common
 *
 * @author cjacobsen
 */
abstract class ENCommon {

    //put your code here

    public static $strings = array(
        'Administrator Full Name' => 'Administrator',
        'Login' => 'Login',
        'Remember Username' => 'Remember Username',
        'Remember Me' => 'Remember Me',
        'Username' => 'Username',
        'Password' => 'Password'
    );

    public static function get($name) {
        if (isset(Self::$strings[$name]) and Self::$strings[$name] != null) {
            return Self::$strings[$name];
        }
        return 'No language reference found for ' . $name;
    }

}
