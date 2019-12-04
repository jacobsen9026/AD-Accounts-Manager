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
use system\Core;
use system\CoreException;
use app\AppLogger;
use system\Request;
use app\config\Router;
use app\config\MasterConfig;
use app\Layout;

class CoreApp extends Parser {

//put your code here

    public $config;
    public $request;
    public $session;
    public $router;
    private $coreLogger;
    public $controller;
    public $debugLog;
    public $logger;
    public $output;
    public $route;
    public $outputBody;
    public $layout;
    public $user;
    public static $instance;

    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     *
     * @param Core $core
     */
    function __construct(Request $req, CoreLogger $cLogger) {

        $this->user = "this";
        self::$instance = $this;
        /*
         * Start up the app logger
         */
        $this->coreLogger = $cLogger;
        $this->logger = new AppLogger;
        $this->logger->info("The app logger has been created");
        /*
         * Load the app configuration
         */
        $this->config = new MasterConfig($this);
        /*
         * Set the php errror mode repective of the setting
         * in the webConfig.
         */
        $this->setErrorMode();
        /*
         * Load the request into the app
         */
        $this->request = $req;
    }

    /**
     * @return App
     */
    public function run() {
        echo "test";
        try {
            $this->route();
            $this->control();
        } catch (\Exception $ex) {
            $this->logger->error($ex);
        }

        $this->layout();
        //var_dump($this->output);
        if (!$this->inDebugMode()) {
            $this->logger = null;
        }

        return array($this->output, $this->logger);
    }

    /**
     *
     * @param type $string
     */
    public function debug($string) {
        if (is_array($string)) {
            $string = $this->debugArray($string);
        }
        $string = str_replace("\n", "", $string);
        $bt = debug_backtrace(1);
        $caller = array_shift($bt);
        $caller["file"] = str_replace("\\", "/", $caller['file']);
        $consoleMessage = $caller["file"] . ":" . $caller["line"] . ' ' . $string;
        $htmlMessage = $caller["file"] . ":" . $caller["line"] . "<br/>" . $string;
        $this->debugLog[] = $consoleMessage;
        //var_dump($this->debugLog);
    }

    /**
     *
     * @param type $string
     */
    public function show($string) {
        $this->outputBody .= $string;
    }

    public function route() {
        /*
         * Build a router based on the current app state
         */
        $this->router = new Router($this);
        /*
         * Route the app state and store the route
         */
        $this->route = $this->router->route();
        //var_dump($this->route);
    }

    public function control() {

        if ($this->controller = Factory::createController($this)) {
            $method = $this->route[1];
            if (method_exists($this->controller, $method)) {
                $this->outputBody .= $this->controller->$method();
                //var_dump($this->outputBody);
            }
        } else {
            echo "No Controller found by name of " . $this->router->module;
            //return false;
        }
    }

    public function layout() {
        $this->layout = new Layout($this);
        $this->output = $this->layout->apply();
        //var_dump($this->output);
    }

    private function setErrorMode() {
        if ($this->inDebugMode()) {
            enablePHPErrors();
        } else {
            disablePHPErrors();
        }
    }

    private function inDebugMode() {
        if ($this->config->webConfig->getDebug()) {
            return true;
        } else {
            return false;
        }
    }

}

?>
