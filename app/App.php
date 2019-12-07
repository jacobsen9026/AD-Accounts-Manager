<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app;

/**
 * Description of App
 *
 * @author cjacobsen
 */
use system\Core;
use system\CoreApp;

class App extends CoreApp {

    function __construct(\system\Request $req, \system\SystemLogger $cLogger) {

        parent::__construct($req, $cLogger);
    }

    public function start() {
        /*
         * Load the app configuration
         */
        $this->loadConfig();
        //$this->saveConfig();
        /*
         * Place any prep processing required before running the app here.
         * No application resources are available at this point.
         */



        $output = $this->run();
        /*
         * Place any post processing required after running the app here.
         */
        return $output;
    }

}

?>