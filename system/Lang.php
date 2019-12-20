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
        $requestedLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        if (self::langExists($requestedLang)) {

            $target = '\app\lang\\' . $requestedLang . '\\' . strtoupper($requestedLang) . 'Common';
        } else {
            $target = '\app\lang\\' . DEFAULT_LANG . '\\' . strtoupper(DEFAULT_LANG) . 'Common';
        }
        //echo $target;
        return $target::get($name);
    }

    private static function langExists($lang) {
        if (file_exists(APPPATH . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . strtoupper($lang) . 'Common.php')) {

            return true;
        }
        app\AppLogger::get()->warning("Language reference for " . $lang . " was not found.");
        return false;
    }

}
