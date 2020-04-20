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

namespace system\app;

/**
 * Description of App
 *
 * @author cjacobsen
 *
 * This is the main Application class. It manages all steps of app execution. The application flow is as follows.
 * AppLogger->Config->Routing->Controlling->Layout->Output back to core
 *
 * No application specific data/functions should be present in this class. That should be utilized in classes within the App namespace.
 */
use system\app\Route;
use system\app\AppErrorHandler;
use system\SystemLogger;
use system\Factory;
use system\Request;
use system\app\Router;
use system\Parser;
use app\config\MasterConfig;
use app\models\user\User;
use app\models\user\Privilege;
use system\app\auth\PermissionHandler;

class App extends Parser {

    use RequestRedirection;

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

    /** @var Route|null The system logger */
    public $route;

    /** @var string|null The system logger */
    public $outputBody;

    /** @var Layout|null The system logger */
    public $layout;

    /** @var AppOutput The application output */
    public $appOutput;

    /** @var User|null The system logger */
    public $user;
    public static $instance;

    function __construct(Request $req, SystemLogger $cLogger) {

        //session_destroy();
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
        $this->appOutput = new AppOutput();
        $this->request = $req;
        /*
         * Define the Grade Codes
         * @DEPRECATED
         * Use Grade Definitions Table in database
         */
        define('GRADE_CODES', array('PK3', 'PK4', 'K', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'));
    }

    /**
     * Get the current App instance
     * @return App
     */
    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Load the application configuration
     */
    public function loadConfig() {
        //$this->config = new MasterConfig();
        $this->coreLogger->info("The app config has been loaded");
        define('GAMPATH', CONFIGPATH . DIRECTORY_SEPARATOR . "google");

        /*
         * Set the php errror mode repective of the setting
         * in the webConfig.
         */
        $this->setErrorMode();
    }

    public function loadUser() {

        $this->user = Session::getUser();
        $cookieName = User::USER . '_' . $this->user->username;
        $cookieData = Cookie::get($cookieName);
        //var_dump($cookieData);
        if ($cookieData != false) {
            $this->logger->info("Loading user from cookie");
            $cookieUser = unserialize(\system\Encryption::decrypt($cookieData));
            if ($cookieUser instanceof User) {
                $this->user = $cookieUser;
            } else {
                Cookie::delete($cookieName);
                $this->user->save();
            }
        } else {
            $this->logger->warning("Cookie not found");
        }
        $this->logger->info($this->user);
    }

    /**
     * Run the application
     * @return AppOutput
     */
    public function run() {

        $this->logger->debug("Creating Session");
        User::load($this);
        try {
            $this->route();
            /**
             * Is the current user allowed to access this uri?
             * If not, this function changes the route to 403 error page
             */
            $this->checkPermission();

            /**
             * This is where the magic happens. The control function calls the class and method
             * determined by the routing.
             */
            $this->control();
        } catch (\Exception $ex) {
            /**
             * If a minor error happens during routing or permission checks we get kick up to here.
             * We log the error to the app logger so we can debug later.
             */
            $this->logger->error($ex);
            $this->appOutput->setBody($ex->getMessage());
        }
        /**
         * Now we need to apply the customized header/footer/styling for the
         * current user and the current page.
         */
        $this->layout();
        /**
         * Lets destroy the app logger if the app isn't in debug mode.
         * We don't want to risk sending any private information.
         */
        if (!$this->inDebugMode()) {
            $this->logger = null;
        }
        /**
         * We need to inject the logs into the appOutput so the core can handle it.
         */
        //$this->appOutput->setBody($this->output);
        $this->appOutput->setLogs($this->logger);
        return $this->appOutput;
    }

    public function route() {
        /*
         * HTTPS redirect check
         * If database setting for https redirect is set,
         * check that the protocol used is actually https for
         * this request. If it isn't redirect the request to https
         */
        $this->handleHttpsRedirect();
        /*
         * Hostname redirect check
         * If database setting for the website FQDN is set,
         * check that the request used the database value.
         *  If it isn't redirect the request to the database
         * stored FQDN value
         */
        $this->handleHostnameRedirect();
        /*
         * Build a router based on the current app state
         */
        $this->router = new Router($this);
        /*
         * Route the app state and store the route
         */
        $this->route = $this->router->route();
        $this->logger->debug($this->route);
    }

    /**
     * Check against the config database's permission table that the current user has the
     * required privilege to access this uri
     */
    private function checkPermission() {

        try {
            $required = PermissionHandler::handleRequest($this->route, $this->user);
            if ($this->user->privilege < $required) {
                $hasPermission = false;
                $this->route = array('Home', 'show403');
            } else {
                $hasPermission = true;
            }
        } catch (Exception $ex) {

        }
        $this->logger->debug('Has Permission: ' . $hasPermission);
    }

    /**
     * Checks if request should be redirected to HTTPS based on app settings
     */
    private function handleHttpsRedirect() {
        $this->logger->debug("Protocol: " . $this->request->protocol);
        $this->logger->debug("Hostname: " . ($_SERVER["SERVER_NAME"]));
        if ($this->request->protocol == "http" && \app\models\AppConfig::getForceHTTPS()) {
            $this->redirect("https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
        }
    }

    /**
     * Checks if request should be redirected to a different FQDN based on app settings
     */
    private function handleHostnameRedirect() {
        $this->logger->debug("Hostname: " . ($_SERVER["SERVER_NAME"]));
        if (strtolower($_SERVER["SERVER_NAME"]) != strtolower(\app\models\AppConfig::getWebsiteFQDN()) and \app\models\AppConfig::getWebsiteFQDN() != "") {
            $this->redirect($this->request->protocol . "://" . strtolower(\app\models\AppConfig::getWebsiteFQDN()) . $_SERVER["REQUEST_URI"]);
        }
    }

    /**
     * Executes the Controller portion of the application
     *
     * @return null
     */
    public function control() {
        /*
         * Check that the user is logged on and if not, set the route to the login screen
         */
        //var_dump($_SESSION);
        if (!isset($this->user) or $this->user == null or $this->user->privilege <= Privilege::UNAUTHENTICATED) {
            $this->logger->warning('user not logged in');
            // Change route to login if not logged in.
            //$this->route;
            $login = new \app\controllers\Login($this);
            $this->appOutput->appendBody($login->index());
            return;
        } else {
            /*
             * User is logged in so update the timeout to reflect new activity
             */
            Session::updateTimeout();
        }
        /*
         * Build the controller based on the previously computed settings
         */
        if ($this->controller = Factory::buildController($this)) {

            $this->logger->debug($this->route);
            $method = $this->route->getMethod();
            if (method_exists($this->controller, $method)) {
                // Check if there is a parameter set from the request

                $this->logger->debug($this->route->getMethod());
                if (empty($this->route->getData())) {
                    $this->appOutput->appendBody($this->controller->$method());
                } else {
                    $this->logger->debug($this->route->getData());
                    $this->appOutput->appendBody($this->controller->$method($this->route->getData()));
                }
                //var_dump($this->outputBody);
            } else {
                $this->logger->warning("No method found by name of " . $this->route->getMethod() . ' in the controller ' . $this->route->getControler());

                $this->appOutput->appendBody($this->view('errors/405'));
            }
            /*
             * If no controller could be created, it's because the class doesnt exists or is setup wrong
             * Show 404
             */
        } else {
            $this->logger->warning("No Controller found by name of " . $this->router->controller);
            $this->appOutput->appendBody($this->view('errors/404'));
        }
    }

    /**
     * Applies theming, menus, modals, and styling
     */
    public function layout() {
        $this->layout = new Layout($this);
        $this->appOutput->setBody($this->layout->apply());
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

    /**
     * Check if the application is currently in debug mode
     * @return boolean
     */
    public function inDebugMode() {

        if (\app\models\AppConfig::getDebugMode()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the application database ID
     * @return int Always returns 1
     */
    public static function getID() {
        /**
         * We always return one because there is currently only one application running on this core.
         * It allows flexabillity if that ever changes.
         */
        return '1';
    }

}

?>