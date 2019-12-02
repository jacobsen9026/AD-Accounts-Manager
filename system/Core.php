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
        define('VIEWPATH', ROOTPATH . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "views");
        /**
         * Auto-load all classes in directories specified within class
         */
        try {
            $this->initializeApp();
        } catch (CoreException $ex) {
            $this->debug($ex);
        }
        /**
         * Run Web App
         */
        try {
            $this->execute();
        } catch (CoreException $ex) {
            $this->debug($ex);
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

    public function debug($string) {
        $string = str_replace("\n", "", $string);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);
        $caller["file"] = str_replace("\\", "/", $caller['file']);
        $consoleMessage = "Called From: " . $caller["file"] . ":" . $caller["line"] . ' ' . $string;
        $htmlMessage = "Called From: " . $caller["file"] . ":" . $caller["line"] . "<br/>" . $string;
        $this->debugLog[] = $consoleMessage;
    }

    public function debugArray($array) {
        if (isset($array)) {
            $message = "<div>";
            foreach ($array as $name => $option) {
                if (is_array($option)) {
                    $message = $message . "<strong>" . $name . "</strong><br/>";
                    foreach ($option as $name => $option2) {

                        $message = $message . $name . ": " . var_export($option2, true) . "<br/>";
                    }
                } else {
                    $message = $message . "<strong>" . $name . "</strong><br/>" . var_export($option, true) . "<br/>";
                }
                $message = $message . "<br/>";
            }
            $message = $message . "</div>";
            return $message;
        }
    }

}

?>