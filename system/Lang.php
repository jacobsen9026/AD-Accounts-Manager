<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system;

/**
 * Description of Lang
 *
 * @author cjacobsen
 */
abstract class Lang {

    //put your code here

    public static function get($name) {
        $target = '\app\lang\\' . LANG . '\\' . strtoupper(LANG) . 'Common';
        //echo $target;
        return $target::get($name);
    }

}
