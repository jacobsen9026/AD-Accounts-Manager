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
require './system/Autoloader.php';

use jacobsen\app\App;

class Core {

    public $file;
    public $config;
    public $request;
    public $auth;
    public $router;
    public $factory;
    public $renderer;
    public $output;
    public $debugLog;
    private $app;

    public function run() {
        /**
         * Declare ROOTPATH constant which is used for all file interactions
         */
        define('ROOTPATH', getcwd());
        /**
         * Auto-load all classes in directories specified within class
         */
        Autoloader::run($this);
        /**
         * Initialize the app config, request, authoriztation, and router
         */
        $this->initializeApp();
        /**
         * Run Web App
         */
        $this->app = new App($this);
        $this->output = $this->app->start();
        //$this->route();
        /**
         * Draw the request and deliver back to user
         */
        $this->render();
    }

    private function initializeApp() {
        /**
         * Initialize all core systems
         */
        $this->request = new Request($this);
        $this->renderer = new Renderer($this);
        //$this->auth = new Authorization($this);
        //$this->router = new Router($this);
    }

    private function render() {
        /**
         * Create instance of renderer
         * Render the body into the full response
         */
        $this->renderer = new Renderer($this);
        $this->renderer->draw($this);
    }

    public function addToBody($string) {
        /**
         * Add an element to the end of the body
         */
        echo $string;
        $this->output .= $string;
    }

    public function debug($string) {
        $string = str_replace("\n", "", $string);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);
        $caller["file"] = str_replace("\\", "/", $caller['file']);
        $consoleMessage = "Called From: " . $caller["file"] . ":" . $caller["line"] . ' ' . $string;
        $htmlMessage = "Called From: " . $caller["file"] . ":" . $caller["line"] . "<br/>" . $string;
        $this->debugLog[] = $consoleMessage;
    }

}

?>