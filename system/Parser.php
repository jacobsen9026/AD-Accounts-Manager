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

    //put your code here
    public function view($view) {
        $view = $this->sanitize($view);

        $path = VIEWPATH . DIRECTORY_SEPARATOR . $view . ".php";
        //echo $path;
        if (file_exists($path)) {
            ob_start();
            include $path;
            return ob_get_clean();
        }
        return false;
    }

    public function parseConfig($file) {
        $file = $this->sanitize($file);

        $path = ROOTPATH . DIRECTORY_SEPARATOR . $file . ".php";
        //echo $path;
        if (file_exists($path)) {
            //ob_start();
            include $path;
            return true;
            //return ob_get_clean();
        }
        return false;
    }

    public function sanitize($path) {
        if ($path[0] == "/" or $path[0] == "\\") {
            $path = substr($path, 1);
        }
        $path = str_replace(array('/', '\\'), strval(DIRECTORY_SEPARATOR), $path);
        return $path;
    }

}
