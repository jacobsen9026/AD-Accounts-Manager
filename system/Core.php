<?php

/*
 * The MIT License
 *
 * Copyright 2019 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace system;

/**
 * Description of App
 *
 * @author cjacobsen
 */
require './system/Autoloader.php';

use system\app\App;
use system\common\CommonApp;
use system\CoreException;
use system\SystemLogger;

class Core {

    /** @var Parser|null The view parser */
    private $parser;

    /** @var SystemLogger|null The system logger */
    public $logger;

    /** @var Request|null The Request */
    public $request;

    /** @var Renderer|null The output renderer */
    public $renderer;

    /** @var string|null The application output */
    public $appOutput;

    /** @var AppLogger|null The application logger */
    public $appLogger;

    /** @var App|null The App */
    public $app;

    /** @var Core|null */
    public static $instance;

    function __construct() {
        /*
         * Create
         * Start Session
         * Declare ROOTPATH constant which is used for all file interactions
         */
        if (!isset($_SESSION)) {
            session_start();
        }

        define('ROOTPATH', getcwd());
        //Enable Error Reporting for core until the system config is loaded
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        self::$instance = $this;
    }

    /**
     *
     * @return Core
     */
    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function run() {
        /**
         * BEGIN
         *
         */
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
            $this->logger->error($ex);
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

    public function abort($message = null) {
        $this->logger->error("Aborting App Execution!");
        $this->logger->error($message);
        $this->appLogger = $this->app->logger;
        $this->appOutput = null;
        $this->app = null;
        try {
            $this->render();
        } catch (CoreException $ex) {
            var_dump($ex);
        }
    }

    /**
     * Initialize all core systems
     */
    private function initializeApp() {
        ini_set('file_uploads', true);
        Autoloader::run($this);
        /*
         * Load the parser in the core since it cannot
         * extend the parser.
         */
        new CoreErrorHandler();

        $this->parser = new Parser();
        /*
         * Load the system logger
         */

        $this->logger = new SystemLogger();
        $this->logger->info("Logger started");
        $this->logger->debug("Session Export Below:");
        $this->logger->debug($_SESSION);
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
        $this->request = new Request();
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


        //ob_start();
        $this->appOutput = $this->runApp();
        $this->appLogger = $this->appOutput[1];
        $this->appOutput = $this->appOutput[0];
        //ob_flush();
        new CoreErrorHandler();
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
            if (method_exists($this->app, 'run')) {

                return $this->app->run();
            } else {
                echo("The " . APPCLASS . " does not have a run() method");
                exit;
            }
        } else {
            echo("The " . APPCLASS . " class was not found");
            exit;
        }

        /*
         * If we were not in debug mode we can flush the output
         * buffer to prepare for the render.
         */
    }

    /**
     * Draw the request and deliver back to user
     *
     */
    private function render() {
        /*
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
            $this->logger->info("Enabling PHP Errors");
            enablePHPErrors();
        } else {

            $this->logger->info("Disabling PHP Errors");
            disablePHPErrors();
        }
    }

    /**
     *
     * @return boolean
     */
    public function inDebugMode() {
        if (defined('DEBUG_MODE')and DEBUG_MODE) {
            return true;
        } else {
            return false;
        }
    }

}

?>