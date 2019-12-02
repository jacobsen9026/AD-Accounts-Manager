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

        spl_autoload_register(function ($class) {
            //var_dump($class);
            $filename = ROOTPATH . DIRECTORY_SEPARATOR . $class . '.php';
            if (!class_exists($class)) {
                if (file_exists($filename)) {
                    //$core->debug($filename);
                    require $filename;
                }
            }
        });


        /*
          require(ROOTPATH . '/system/File.php');
          $files = File::getFiles(\ROOTPATH . '/system');
          //var_dump($files);
          if ($files) {
          foreach ($files as $class) {
          if (strpos($class, "Core.php") == false and strpos($class, "File.php") == false and strpos($class, "Autoloader.php") == false) {
          $className = str_replace(".php", '', str_replace(ROOTPATH, '', $class));
          $core->debug($class);
          if (!class_exists($className)) {
          if (file_exists($class)) {
          require $class;
          //echo "loaded class: " . $class;
          }
          }
          }
          }
          }
          /*
          foreach (File::getAutoloadFiles(ROOTPATH . DIRECTORY_SEPARATOR . "app") as $file) {
          $className = str_replace(".php", '', str_replace(ROOTPATH, '', $class));
          var_dump($class);
          if (!class_exists($className)) {
          require ($file);
          }
          }
         *
         */
    }

}

?>