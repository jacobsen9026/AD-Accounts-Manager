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

namespace System;

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

use System\AppOutput;
use System\CoreException;
use System\SystemLogger;
use System\Log\CommonLogger;

class Core
{

    /** @var Parser|null The view parser that enables printing of views */
    private $parser;

    /** @var SystemLogger|null The system logger */
    public static $systemLogger;

    /**
     *
     * @var DatabaseLogger
     */
    public static $databaseLogger;
    /**
     *
     * @var DatabaseLogger
     */
    public static $postLogger;

    /** @var Request|null The Request object */
    public $request;

    /** @var Renderer|null The application output renderer */
    public $renderer;

    /** @var AppOutput The application output */
    public $appOutput;

    /** @var CommonLogger|null The application logger */
    public $appLogger;

    /** @var App|null The App Instance */
    public $app;

    /** @var Core|null This core instance */
    public static
        $instance;

    function __construct()
    {
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
        ini_set('display_errors', true);
        ini_set('display_startup_errors', true);
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
    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Start the system core running. This should be called from the public php index file
     */
    public function run()
    {
        /**
         * BEGIN
         *
         * Initialize the application
         */
        try {
            $this->initialize();
        } catch (CoreException $ex) {
            self::$systemLogger->error($ex);
        }
        /**
         * Run the App
         */
        try {
            $this->execute();
        } catch (CoreException $ex) {
            // $this->abort($ex
            $this->appOutput->setBody($ex->getMessage());
            self::$systemLogger->error($ex);
        }
        /**
         * Return the response to the user
         */
        try {
            $this->render();
        } catch (CoreException $ex) {
            self::$systemLogger->error($ex);
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
     *
     * @param type $message
     */
    public function abort($message = null)
    {
        self::$systemLogger->error("Aborting App Execution!");
        self::$systemLogger->error($message);
        $this->appLogger = ($this->app->logger);
//$this->appOutput = null;
//$this->app = null;
        try {
            $this->render();
        } catch (CoreException $ex) {
            self::$systemLogger->error($ex);
        }
    }

    /**
     * Initialize all core systems for application running
     */
    private function initialize()
    {
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

        self::$databaseLogger = new DatabaseLogger();
        self::$postLogger = new PostLogger();
        self::$systemLogger = new SystemLogger();
        self::$systemLogger->info("Logger started");
        /**
         * Log the session to the core logger
         */
        self::$systemLogger->debug("Session Export Below:");
        self::$systemLogger->debug($_SESSION);
        /*
         * The following statement must not ever be removed.
         * Everything depends on the system config being
         * loaded.
         */
        $this->parser->include("system/Config");
        self::$systemLogger->info("Core config loaded");
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
        self::$systemLogger->info("Request created");
        /*
         * Initialization complete return to run()
         */
    }

    /**
     * Executes the application class by calling the runApp() method
     */
    private function execute()
    {
        self::$systemLogger->info("App starting");
        /**
         * We prep the output in case something goes wrong with the app
         */
        $this->appOutput = new AppOutput();
        /**
         * Run app
         */
        $this->appOutput = $this->runApp();
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
        self::$systemLogger->info("App execution completed");
    }

    private function runApp()
    {

        /*
         * Check that the defined primary app class in the
         * config actually exists and then launch it if it is.
         */
        if (class_exists(APPCLASS)) {
            $class = APPCLASS;
            $this->app = new $class($this->request, self::$systemLogger);
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
    private function render()
    {
        /*
         * Create instance of renderer
         * Render the body into the full response
         */
        self::$systemLogger->info("Creating renderer");
        $this->renderer = new Renderer($this);
        self::$systemLogger->info("Renderer created");
        self::$systemLogger->info("Call renderer to draw");
        $this->renderer->draw($this);
    }

    /**
     * Sets the error mode according to the core debug setting
     *
     * Debug On: Print all errors
     * Debug Off: Suppress Errors
     */
    private function setErrorMode()
    {
        if ($this->inDebugMode()) {
            self::$systemLogger->info("Enabling PHP Errors");
            enablePHPErrors();
        } else {

            self::$systemLogger->info("Disabling PHP Errors");
            disablePHPErrors();
        }
    }

    /**
     * Returns true if the core is in debug mode,
     * false if it is not.
     *
     * @return boolean
     */
    public static function inDebugMode()
    {
        if (defined('DEBUG_MODE') and DEBUG_MODE) {
            return true;
        } else {
            return false;
        }
    }

    public static function getAppClass()
    {
        return APPCLASS;
    }

}

?>