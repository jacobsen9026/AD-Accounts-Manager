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
    }

    private function initializeApp() {
        /**
         * Initialize all core systems
         */
        Autoloader::run($this);

        $this->parser = new Parser();
        $this->logger = new SystemLogger();
        $this->logger->info("Logger started");
        $this->parser->include("system/Config");
        $this->logger->info("Core config loaded");
        var_dump(DEBUG_MODE);

        $this->setErrorMode();
        $this->request = new Request($this);

        $this->logger->info("Request created");
    }

    private function execute() {

        $this->logger->info("App starting");
        disablePHPErrors();
        ob_start();
        $this->app = new App($this->request);
        $this->output = $this->app->start();
        ob_get_clean();
        $this->setErrorMode();
        $this->logger->info("App execution completed");
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

    private function setErrorMode() {
        if (defined('DEBUG_MODE')and DEBUG_MODE) {
            enablePHPErrors();
        } else {
            disablePHPErrors();
        }
    }

}

?>