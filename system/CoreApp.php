<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jacobsen\system;

/**
 * Description of App
 *
 * @author cjacobsen
 */
use jacobsen\system\Core;
use jacobsen\app

;

class CoreApp {

//put your code here
    public $core;
    public $router;
    public $controller;
    public $debugLog;
    public $request;
    public $output;
    public $route;
    public $outputBody;
    public $layoutProcessor;

    /**
     *
     * @param Core $core
     */
    function __construct(Core $core) {
        $this->core = $core;
        $this->request = $core->request;
    }

    public function run() {
        $this->route();
        $this->control();
        $this->layout();
        //$this->debug("debug test");
        return $this->output;
        /*


         */
        //return "Success";
    }

    /**
     *
     * @param type $string
     */
    public function debug($string) {
        $string = str_replace("\n", "", $string);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);
        $caller["file"] = str_replace("\\", "/", $caller['file']);
        $consoleMessage = "Called From: " . $caller["file"] . ":" . $caller["line"] . ' ' . $string;
        $htmlMessage = "Called From: " . $caller["file"] . ":" . $caller["line"] . "<br/>" . $string;
        $this->debugLog[] = $consoleMessage;
    }

    /**
     *
     * @param type $string
     */
    public function show($string) {
        $this->outputBody .= $string;
    }

    public function route() {
        $this->route = $this->router->route();
        //var_dump($this->route);
        // . $this->printDebug();
    }

    public function control() {

        if ($this->controller = Factory::createController($this)) {
            $method = $this->route[1];
            $this->outputBody .= $this->controller->$method($this);
            //var_dump($this->outputBody);
        } else {
            echo "No Controller found by name of " . $routedClass;
            return false;
        }
    }

    public function layout() {
        $this->layoutProcessor = new app\layouts\Layout($this);
        $this->output = $this->layoutProcessor->apply($this->outputBody);
    }

}

?>
