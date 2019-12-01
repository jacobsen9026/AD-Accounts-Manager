<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jacobsen\system;

/**
 * Description of Factory
 *
 * @author cjacobsen
 */
use jacobsen\app\App;

abstract class Factory {

    //put your code here
    public static function createController(App $app) {
        $controllerPath = '\\jacobsen\\app\\controllers\\';
        //echo $app->router->module();
        $classname = $controllerPath . $app->router->module;
        //var_dump($router);
        //echo $app->router->module();
        if (class_exists($classname)) {


            return new $classname($app);
        }
        return false;
    }

}

?>
