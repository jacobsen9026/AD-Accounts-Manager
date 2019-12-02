<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system;

/**
 * Description of Factory
 *
 * @author cjacobsen
 */
use app\App;

abstract class Factory {

    //put your code here
    public static function createController(App $app) {
        //var_dump($app);
        $controllerPath = '\\app\\controllers\\';
        //echo $app->route[0];
        $classname = $controllerPath . $app->route[0];
        //echo $classname;
        //var_dump($router);
        //echo $app->router->module();
        if (class_exists($classname)) {


            return new $classname($app);
        }
        return false;
    }

}

?>
