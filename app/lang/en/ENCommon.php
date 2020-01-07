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
use app\lang\Language;

abstract class ENCommon {

    use Language;

    //put your code here

    public static $strings = array(
        'Administrator Full Name' => 'Administrator',
        'Login' => 'Login',
        'Remember Username' => 'Remember Username',
        'Remember Me' => 'Remember Me',
        'Username' => 'Username',
        'Password' => 'Password'
    );
    public static $help = array(
        'User Search' => 'Can also enter first or last name to search for username.'
    );

}
