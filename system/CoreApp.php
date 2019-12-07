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
use app\AppErrorHandler;

class CoreApp extends Parser {
//put your code here

    /** @var MasterConfig|null The system logger */
    public $config;

    /** @var Request|null The system logger */
    public $request;

    /** @var Session|null The system logger */
    public $session;

    /** @var Router|null The system logger */
    public $router;

    /** @var SystemLogger|null The system logger */
    private $coreLogger;

    /** @var Controller|null The system logger */
    public $controller;

    /** @var string|null The system logger */
    public $debugLog;

    /** @var AppLogger|null The system logger */
    public $logger;

    /** @var string|null The system logger */
    public $output;

    /** @var Array|null The system logger */
    public $route;

    /** @var string|null The system logger */
    public $outputBody;

    /** @var Layout|null The system logger */
    public $layout;

    /** @var User|null The system logger */
    public $user;
    public $configFilePath;
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
    function __construct(Request $req, SystemLogger $cLogger) {

        $this->user = "this";
        self::$instance = $this;
        /*
         * Start up the coreLogger to be used only by the config
         */

        new AppErrorHandler();
        $this->coreLogger = $cLogger;
        /*
         * Set up the appLogger
         */
        $this->logger = new AppLogger;
        $this->coreLogger->info("The app logger has been created");





        /*
         * Load the request into the app
         */
        $this->request = $req;
    }

    /**
     * @return App
     */
    public function run() {
        try {
            $this->route();
            try {
                $this->control();
            } catch (Exception $ex) {
                $this->logger->error($ex);
            }
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

    public function loadConfig() {

        if (file_exists($this->configFilePath)) {

            $this->logger->info('Loading App Config at ' . $this->configFilePath);
            $this->config = unserialize(file_get_contents($this->configFilePath));
        } else {

            $this->config = new MasterConfig();
        }

        $this->coreLogger->info("The app config has been loaded");
        /*
         * Set the php errror mode repective of the setting
         * in the webConfig.
         */
        $this->setErrorMode();
    }

    public function saveConfig() {
        $this->logger->info("Saving Config at " . $this->configFilePath);

        //$this->app->logger = null;
        file_put_contents($this->configFilePath, serialize($this->config));
        //$this->app->logger = $tempLogger;
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

        if ($this->controller = Factory::buildController($this)) {
            $method = $this->route[1];
            if (method_exists($this->controller, $method)) {
                $this->outputBody .= $this->controller->$method();
                //var_dump($this->outputBody);
            }
        } else {
            $this->logger->warning("No Controller found by name of " . $this->router->module);
            $this->outputBody .= $this->view('errors/404');
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

    public function inDebugMode() {
        if ($this->config->webConfig->getDebug()) {
            return true;
        } else {
            return false;
        }
    }

}

?>
