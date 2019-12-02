<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system;

/**
 * Description of App
 *
 * @author cjacobsen
 */
require './system/Autoloader.php';

use app\App;
use system\CoreException;

class Core {

    private $parser;
    public $logger;
    private $request;
    private $session;
    public $auth;
    public $router;
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
        try {
            $this->initializeApp();
        } catch (CoreException $ex) {
            $this->error($ex);
        }
        /**
         * Run Web App
         */
        try {
            $this->execute();
        } catch (CoreException $ex) {
            $this->error($ex);
        }

        //$this->route();
        try {
            $this->render();
        } catch (CoreException $ex) {
            var_dump($ex);
        }
    }

    private function initializeApp() {
        /**
         * Initialize all core systems
         */
        Autoloader::run($this);
        $this->logger = new CoreLogger();
        $this->parser = new Parser();
        $this->parser->parseConfig("/system/Config");
        $this->request = new Request($this);
    }

    private function execute() {

        $this->app = new App($this->request);
        $this->output = $this->app->start();
    }

    private function render() {
        /**
         * Draw the request and deliver back to user
         *
         *
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

    public function debug($message) {
        $message = str_replace("\n", "", $message);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);
        $caller["file"] = str_replace("\\", "/", $caller['file']);

        $this->logger->debug($message, $caller);
    }

    public function warning($message) {
        $message = str_replace("\n", "", $message);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);
        $caller["file"] = $this->parser->sanitize($caller['file']);
        $this->logger->warning($message, $caller);
    }

    public function error($message) {
        $message = str_replace("\n", "", $message);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);
        $caller["file"] = $this->parser->sanitize($caller['file']);
        $this->logger->error($message, $caller);
    }

    public function info($message) {
        $message = str_replace("\n", "", $message);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);
        $caller["file"] = $this->parser->sanitize($caller['file']);
        $this->logger->info($message, $caller);
    }

}

?>