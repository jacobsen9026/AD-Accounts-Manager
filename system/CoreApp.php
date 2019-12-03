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
    public $controller;
    public $debugLog;
    public $debugOutput;
    public $output;
    public $route;
    public $outputBody;
    public $layout;
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
    function __construct(Request $req) {
        self::$instance = $this;
        $this->config = new MasterConfig($this);
        $this->request = $req;
        $this->router = new Router($this);
        ECHO $THIS;
    }

    public function run() {
        try {
            $this->route();
            $this->control();
            $this->layout();
            //$this->debug("debug test");
            //var_dump($this->output);
            return $this->output;
        } catch (AppException $ex) {
            var_dump($ex);
        }
        /*


         */
        //return "Success";
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
            return false;
        }
        $this->debug("Output: " . $this->outputBody);
        //var_dump($this->outputBody);
    }

    public function layout() {
        $this->layout = new Layout($this);
        $this->output = $this->layout->apply();
        //var_dump($this->output);
    }

}

?>