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
    public $moduleController;
    public $renderer;
    public $output;
    public $debugLog;

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
         * Route the request
         */
        $app = new App($this);
        $this->output = $app->run();
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
        //$this->config = new Config($this);
        $this->request = new Request($this);
        //$this->auth = new Authorization($this);
        //$this->router = new Router($this);
    }

    private function route() {
        /**
         * Route the request
         *
         * Call the router and the appropriate class and method to execute
         *
         * Create instance of this class
         *
         * Execute method
         *
         * Pass parameter if set
         */
        $this->router->getRoute($this);
        $routedMethod = $this->router->page;
        if ($this->moduleController = Factory::createController($this)) {
            $this->moduleController->$routedMethod($this);
        } else {
            echo "No Controller found by name of " . $routedClass;
            return false;
        }
    }

    private function render() {
        /**
         * Create instance of renderer
         * Render the body into the full response
         */
        $this->renderer = new Renderer($this);
        $this->renderer->renderView($this);
    }

    public function addToBody($string) {
        /**
         * Add an element to the end of the body
         */
        echo $string;
        $this->output .= $string;
    }

    public function debug($string) {
        $this->debugLog[] = $string;
    }

}

?>