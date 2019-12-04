<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system;

/**
 * Description of CoreParser
 *
 * @author cjacobsen
 */
class Parser {

    public function view($view) {
        $view = $this->sanitize($view);

        $path = VIEWPATH . DIRECTORY_SEPARATOR . $view . ".php";
        //echo $path;
        if (file_exists($path)) {


            ob_start();
            if (include $path) {
                return ob_get_clean();
            }
            ob_get_clean();
        }
        return false;
    }

    public function include($file) {

        $file = $this->sanitize($file);

        $path = ROOTPATH . DIRECTORY_SEPARATOR . $file . ".php";
        //echo $path;
        if (file_exists($path)) {
            //ob_start();
            //echo "loaded";
            include $path;
            return true;
            //return ob_get_clean();
        } else {

            return false;
        }
    }

    public function sanitize($path) {
        if ($path[0] == "/" or $path[0] == "\\") {
            $path = substr($path, 1);
        }
        $path = str_replace(array('/', '\\'), strval(DIRECTORY_SEPARATOR), $path);
        return $path;
    }

    public function bufferVarDump($object) {
        ob_start();
        var_dump($object);
        return ob_get_clean();
    }

}
