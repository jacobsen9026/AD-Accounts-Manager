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
    public static $instance;

    function __construct() {
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

        $this->parser = new Parser();
        $this->logger = new SystemLogger();
        $this->parser->include("/system/Config");
        $this->request = new Request($this);
    }

    private function execute() {
        ob_start();
        $this->app = new App($this->request);
        $this->output = $this->app->start();
        ob_get_clean();
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

}

?>