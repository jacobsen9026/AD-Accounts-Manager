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
use system\SystemLogger;

class Core {

    /** @var Parser|null The view parser */
    private $parser;

    /** @var SystemLogger|null The system logger */
    public $logger;

    /** @var Request|null The Request */
    private $request;

    /** @var Renderer|null The output renderer */
    public $renderer;

    /** @var string|null The application output */
    public $appOutput;

    /** @var AppLogger|null The application logger */
    public $appLogger;

    /** @var App|null The App */
    private $app;
    public static $instance;

    function __construct() {
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        session_start();
        self::$instance = $this;
    }

    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function run() {
        /**
         * BEGIN
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

        try {
            $this->render();
        } catch (CoreException $ex) {
            var_dump($ex);
        }
        /*
         * The request has been completed and the response is being delivered.
         * The app and core, along with all children, are kill.
         * EXIT
         */
    }

    private function initializeApp() {
        /**
         * Initialize all core systems
         */
        Autoloader::run($this);
        /*
         * Load the parser in the core since it cannot
         * extend the parser.
         */
        $this->parser = new Parser();
        /*
         * Load the system logger
         */

        $this->logger = new SystemLogger();
        $this->logger->info("Logger started");
        /*
         * The following statement must not ever be removed.
         * Everything depends on the system config being
         * loaded.
         */
        $this->parser->include("system/Config");
        $this->logger->info("Core config loaded");
        /*
         * Set PHP error mode to reflect setting in system config
         * for DEBUG_MODE
         */
        $this->setErrorMode();
        /*
         * Generate a new request to lay the foundation of the
         * routing to come.
         */
        $this->request = new Request($this);
        $this->logger->info("Request created");
        /*
         * Initialization complete return to run()
         */
    }

    private function execute() {
        $this->logger->info("App starting");
        /*
         * If system debug is enabled, do not buffer
         * the output of the app to prevent it from echoing
         */


        $this->appOutput = $this->runApp();
        $this->appLogger = $this->appOutput[1];
        $this->appOutput = $this->appOutput[0];


        /*
         * Check if the system is in debug and if so set
         * php error settings appropriatly
         */
        //$this->setErrorMode();
        $this->logger->info("App execution completed");
    }

    private function runApp() {

        /*
         * Check that the defined primary app class in the
         * config actual loaded and then launch it if it is.
         */
        if (class_exists(APPCLASS)) {
            $class = APPCLASS;
            $this->app = new $class($this->request, $this->logger);
            return $this->app->start();
        } else {
            $this->logger->error("The app\App class was not found");
        }

        /*
         * If we were not in debug mode we can flush the output
         * buffer to prepare for the render.
         */
    }

    private function render() {
        /**
         * Draw the request and deliver back to user
         *
         * Create instance of renderer
         * Render the body into the full response
         */
        $this->logger->info("Creating renderer");
        $this->renderer = new Renderer($this);
        $this->logger->info("Renderer created");
        $this->logger->info("Call renderer to draw");
        $this->renderer->draw($this);
    }

    private function setErrorMode() {
        if ($this->inDebugMode()) {
            enablePHPErrors();
        } else {

            disablePHPErrors();
        }
    }

    public function inDebugMode() {
        if (defined('DEBUG_MODE')and DEBUG_MODE) {
            return true;
        } else {
            return false;
        }
    }

}

?>