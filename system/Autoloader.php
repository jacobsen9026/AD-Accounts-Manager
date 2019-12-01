<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jacobsen\system;

/**
 * Description of Autoloader
 *
 * @author cjacobsen
 */
abstract class Autoloader {

    public static function run(Core $core) {

        require(ROOTPATH . '/system/File.php');
        $files = File::getFiles(\ROOTPATH . '/system');
        if ($files) {
            foreach ($files as $class) {
                if (strpos($class, "Core.php") == false and strpos($class, "File.php") == false and strpos($class, "Autoloader.php") == false) {
                    if (file_exists($class)) {
                        require $class;
                        //echo "loaded class: " . $class;
                    } else {
                        $core->show404();
                    }
                }
            }
        }
        foreach (File::getAutoloadFiles(ROOTPATH . DIRECTORY_SEPARATOR . "app") as $file) {
            require ($file);
        }
    }

}

?>