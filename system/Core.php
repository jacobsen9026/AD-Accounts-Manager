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
 * Description of Core
 *
 * This is the main system core class. It is the first thing
 * called upon receiving an http request. It handles bootstrapping
 * the system classes and loading core configuration. From there it
 * triggers the application to run and receives it's output and log.
 * Finally the renderer compiles the app and core data together into
 * a final HTTP response for the user.
 */
require './system/Autoloader.php';

use system\AppOutput;
use system\CoreException;
use system\SystemLogger;
use system\common\CommonLogger;

class Core {

    /** @var Parser|null The view parser that enables printing of views */
    private $parser;

    /** @var SystemLogger|null The system logger */
    public $logger;

    /**
     *
     * @var CommonLogger
     */
    public static $coreLogger;

    /** @var Request|null The Request object */
    public $request;

    /** @var Renderer|null The application output renderer */
    public $renderer;

    /** @var AppOutput The application output */
    public $appOutput;

    /** @var AppLogger|null The application logger */
    public $appLogger;

    /** @var App|null The App Instance */
    public $app;

    /** @var Core|null This core instance */
    public static $instance;

    function __construct() {
        /*
         * Create Core
         * Start Session
         * Declare ROOTPATH constant which is used for all file interactions
         */
        if (!isset($_SESSION)) {
            session_start();
        }
        define('ROOTPATH', getcwd());
        /*
         * Enable Error Reporting for core until the system config is loaded
         */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        ini_set('file_uploads', true);
        /*
         * Store new instance for abstract reference
         */
        self::$instance = $this;
    }

    /**
     * Gets the current core instance
     *
     * @return Core
     */
    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Start the system core running. This should be called from the public php index file
     */
    public function run() {
        /**
         * BEGIN
         *
         * Initialize the application
         */
        try {
            $this->initializeApp();
        } catch (CoreException $ex) {
            $this->error($ex);
        }
        /**
         * Run the App
         */
        try {
            $this->execute();
        } catch (CoreException $ex) {
            // $this->abort($ex
            $this->appOutput->setBody($ex->getMessage());
            $this->logger->error($ex);
        }
        /**
         * Return the response to the user
         */
        try {
            $this->render();
        } catch (CoreException $ex) {
            var_dump($ex);
        }
        /*
         * The request has been completed and the response is being delivered.
         * The app and core have completed execution and PHP is handed off
         * back to the public index.php file immediately following the run().
         * EXIT
         */
    }

    /**
     * In the event of a CoreException, immediately stops app execution and pulls the current AppLogger state.
     * Should not be used by other classes.
     * @param type $message
     */
    public function abort($message = null) {
        $this->logger->error("Aborting App Execution!");
        $this->logger->error($message);
        $this->appLogger = ($this->app->logger);
        //$this->appOutput = null;
        //$this->app = null;
        try {
            $this->render();
        } catch (CoreException $ex) {
            var_dump($ex);
        }
    }

    /**
     * Initialize all core systems for application running
     */
    private function initializeApp() {
        /**
         * Run autoloader
         */
        Autoloader::run();
        /**
         * By instantiating the CoreErrorHandler we trigger errors and exceptions
         * to be caught with our custom handlers.
         *
         */
        new CoreErrorHandler();

        /*
         * Load the parser in the core since it cannot
         * extend the parser.
         */
        $this->parser = new Parser();
        /*
         * Load the system logger
         */
        self::$coreLogger = new CommonLogger();
        self::$coreLogger->info("Logger started");
        $this->logger = new SystemLogger();
        $this->logger->info("Logger started");
        //var_export($this->coreLogger);
        /**
         * Log the session to the core logger
         */
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

    /**
     * Executes the application class by calling the runApp() method
     */
    private function execute() {
        $this->logger->info("App starting");
        /**
         * We prep the output in case something goes wrong with the app
         */
        $this->appOutput = new AppOutput();
        /**
         * Run app
         */
        $this->appOutput = $this->runApp();
        /**
         * This will go away but for backwards compatibility, I'm doing this
         */
        $this->appLogger = $this->appOutput->getLogs();
        /**
         * We need to retake control of the run-time errors from the app
         * if it was set to do so.
         *
         * So we re-instantiate the CoreErrorHandler
         */
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
         * config actually exists and then launch it if it is.
         */
        if (class_exists(APPCLASS)) {
            $class = APPCLASS;
            $this->app = new $class($this->request, $this->logger);
            /**
             * Make sure that this APPCLASS has the run method otherwise what's the point
             */
            if (method_exists($this->app, 'run')) {
                /**
                 * Let's run the app and return what it gives back to the core execution
                 */
                return $this->app->run();
            } else {
                throw new CoreException("The " . APPCLASS . " does not have a run() method", CoreException::APP_MISSING_RUN);
                echo("The " . APPCLASS . " does not have a run() method");

                exit;
            }
        } else {
            throw new CoreException("The " . APPCLASS . " class was not found", CoreException::APP_MISSING);
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

    /**
     * Sets the error mode according to the core debug setting
     *
     * Debug On: Print all errors
     * Debug Off: Suppress Errors
     */
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
     * Returns true if the core is in debug mode,
     * false if it is not.
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

    public static function getLogger() {
        return self::$coreLogger;
    }

}

?>