<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system;

/**
 * Description of Autoloader
 *
 * @author cjacobsen
 */
abstract class Autoloader {

    public static function run(Core $core) {

        include(ROOTPATH . DIRECTORY_SEPARATOR . "system" . DIRECTORY_SEPARATOR . "CoreFunctions.php");
        spl_autoload_register(function ($class) {
            //var_dump($class);
            $filename = ROOTPATH . DIRECTORY_SEPARATOR . $class . '.php';
            if (!class_exists($class)) {
                if (file_exists($filename)) {
                    try {
                        require $filename;
                    } catch (Exception $ex) {
                        echo $ex;
                    }
                }
            }
        });
    }

}

?>