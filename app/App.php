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

namespace app;

/**
 * Description of App
 *
 * @author cjacobsen
 */
use system\Core;
use system\app\CoreApp;
use system\app\AppErrorHandler;
use system\CoreException;
use system\Request;
use system\SystemLogger;
use system\Factory;
use app\config\Router;
use app\config\MasterConfig;
use app\Layout;
use app\models\user\User;
use app\models\user\Privilege;

class App extends CoreApp {

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
    public static $instance;

    function __construct(\system\Request $req, \system\SystemLogger $cLogger) {

        //$this->user = "this";
        self::$instance = $this;
        /*
         * Trigger the appErrorHandler to begin until we load the config
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
        $this->loadConfig();
        $this->request = $req;
        /*
         * Load the session
         */
    }

    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function loadConfig() {
        $this->config = new MasterConfig();
        $this->coreLogger->info("The app config has been loaded");
        /*
         * Set the php errror mode repective of the setting
         * in the webConfig.
         */
        $this->setErrorMode();
    }

    public function saveConfig() {
        $this->config->saveConfig();
    }

    /**
     * @return App
     */
    public function run() {
        $this->logger->debug("Creating Session");
        $this->session = new Session();
        $this->session->buildSession();
        $this->user = $this->session->getUser();
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

    public function route() {
        /*
         * Build a router based on the current app state
         */
        $this->router = new Router($this);
        /*
         * Route the app state and store the route
         */
        $this->route = $this->router->route();
        $this->logger->info($this->route);
    }

    public function control() {
        if (!isset($this->user) or $this->user == null or $this->user->privilege <= Privilege::UNAUTHENTICATED) {

            $this->logger->warning('user not logged in');
            $this->route = array('Login', 'index');
        }
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

    /*
     * Set the php errror mode repective of the setting
     * in the webConfig.
     */

    private function setErrorMode() {
        if ($this->inDebugMode()) {
            enablePHPErrors();
        } else {
            disablePHPErrors();
        }
    }

    public function inDebugMode() {
        if ($this->config->web->getDebug()) {
            return true;
        } else {
            return false;
        }
    }

}

?>