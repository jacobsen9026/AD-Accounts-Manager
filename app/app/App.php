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

namespace App\App;

/**
 * Description of App
 *
 * @author cjacobsen
 *
 * This is the main Application class. It manages all steps of app execution. The application flow is as follows.
 * AppLogger->ConfigDatabase->Routing->Controlling->Layout->Output back to core
 *
 * No application specific data/functions should be present in this class. That should be utilized in classes within
 * the App namespace.
 */

use App\Controllers\Controller;
use System\App\AppLogger;
use System\App\Layout;
use System\App\LDAPLogger;
use System\App\RequestRedirection;
use System\App\Route;
use System\App\AppErrorHandler;
use System\App\UserLogger;
use System\Log\CommonLogger;
use System\App\ControllerFactory;
use System\Request;
use System\App\Router;
use App\Models\User\User;
use App\Models\Database\AppDatabase;
use System\AppOutput;
use System\App\Interfaces\AppInterface;
use System\Common\CommonApp;
use System\App\WindowsLogger;
use System\SystemLogger;
use System\Traits\Parser;


class App extends CommonApp implements AppInterface
{

    use RequestRedirection;
    use Parser;

    /** @var SystemLogger|null The system logger */
    private $coreLogger;

    /** @var AppLogger|null The system logger */
    public $logger;

    /** @var string|null The system logger */
    public $output;

    /** @var Route|null The system logger */
    public $route;

    /** @var Layout|null The system logger */
    public $layout;

    /** @var AppOutput The application output */
    public $appOutput;

    /** @var LDAPLogger */
    public $ldapLogger;

    /** @var WindowsLogger */
    public $windowsLogger;

    /** @var UserLogger */
    public $userLogger;

    /**
     *
     * @var Controller
     */
    public $controller;

    /** @var User|null The web user object */
    public $user;

    /**
     *
     * @var App
     */
    public static $instance;

    public static $version = '0.1.0';

    /**
     *
     * @param Request $req
     * @param CommonLogger $cLogger
     */
    function __construct(Request $req, CommonLogger $cLogger)
    {


        self::$instance = $this;
        /**
         * Trigger the appErrorHandler to begin until we load the config
         * Start up the coreLogger to be used only by the config
         */
        new AppErrorHandler();
        $this->coreLogger = $cLogger;


        $this->request = $req;
        /**
         * Set up the appLogger
         */
        $this->logger = new AppLogger;
        $this->coreLogger->info("The app logger has been created");

        /**
         * Set up the ad API LDAP Logger
         */
        $this->ldapLogger = new LDAPLogger();
        $this->logger->info("LDAP logger started");
        /**
         * Set up the ad API LDAP Logger
         */
        $this->windowsLogger = new WindowsLogger();
        $this->logger->info("Windows logger started");

        /**
         * Set up the ad API LDAP Logger
         */
        $this->userLogger = new UserLogger();
        $this->logger->info("user logger started");

        $configDSN = "sqlite:" . APPCONFIGDBPATH;
        new ConfigDatabase($configDSN);

        /**
         * Load the request into the app
         */
        $this->loadConfig();
    }

    /**
     * Get the current App instance
     *
     * @return App
     */
    public static function get(): App
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Load the application configuration
     */
    public function loadConfig(): void
    {
        $this->coreLogger->info("The app config has been loaded");
        define('GAMPATH', CONFIGPATH . DIRECTORY_SEPARATOR . "google");

        /**
         * Set the php errror mode repective of the setting
         * in the webConfig.
         */
        $this->setErrorMode();
    }

    /**
     * Run the application
     *
     * @return AppOutput
     */
    public function run(): AppOutput
    {

        $this->appOutput = new AppOutput($this->request);
        $this->logger->info("Creating Session");
        User::load($this);
        try {
            $this->route();
            /**
             * Is the current user allowed to access this uri?
             * If not, this function changes the route to 403 error page
             *
             * @deprecated
             *
             *
             * $this->checkPermission();
             */
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
        if ($this->inDebugMode()) {
            /**
             * We need to inject the logs into the appOutput so the core can handle it.
             */
            $this->appOutput->addLogger($this->logger)
                ->addLogger($this->ldapLogger)
                ->addLogger($this->windowsLogger)
                ->addLogger($this->userLogger);
        }

        return $this->appOutput;
    }

    /**
     * Processes the incoming request by utilizing
     * the Router to determine a controller, method,
     * and data to be executed this run.
     */
    public function route(): void
    {
        /**
         * HTTPS redirect check
         * If database setting for https redirect is set,
         * check that the protocol used is actually https for
         * this request. If it isn't redirect the request to https
         */
        $this->handleHttpsRedirect();
        /**
         * Hostname redirect check
         * If database setting for the website FQDN is set,
         * check that the request used the database value.
         *  If it isn't redirect the request to the database
         * stored FQDN value
         */
        $this->handleHostnameRedirect();
        /**
         * Build a router based on the current app state
         */
        $this->router = new Router($this);
        /**
         * Route the app state and store the route
         */
        $this->route = $this->router->route();
        $this->logger->debug($this->route);
    }

    /**
     * Checks if request should be redirected to HTTPS based on app settings
     *
     *
     * @todo Should use Request Object
     */
    private function handleHttpsRedirect(): void
    {
        $this->logger->info("Protocol: " . $this->request->getProtocol());
        $this->logger->info("Hostname: " . ($_SERVER["SERVER_NAME"]));
        if ($this->request->getProtocol() == "http" && AppDatabase::getForceHTTPS()) {
            $this->redirect("https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
        }
    }

    /**
     * Checks if request should be redirected to a different FQDN based on app settings
     *
     * @todo Should use Request Object
     */
    private function handleHostnameRedirect(): void
    {
        $this->logger->info("Hostname: " . ($_SERVER["SERVER_NAME"]));
        if (strtolower($_SERVER["SERVER_NAME"]) !== strtolower(AppDatabase::getWebsiteFQDN())) {
            if (AppDatabase::getWebsiteFQDN() != "") {
                $this->redirect($this->request->getProtocol() . "://" . strtolower(AppDatabase::getWebsiteFQDN()) . $_SERVER["REQUEST_URI"]);
            }
        }
    }

    /**
     * Executes the Controller portion of the application
     *
     * @return null
     */
    protected function control(): void
    {
        $this->appOutput = new AppOutput($this->request);
        /*
         * Check that the user is logged on and if not, set the route to the login screen
         */
        if (!isset($this->user) or $this->user === null or $this->user->authenticated === false) {
            $this->logger->warning('user not logged in');
            // Change route to login if not logged in.
            //$this->route;
            $this->controller = new \App\Controllers\Login($this);
            $this->appOutput->appendBody($this->controller->index());
            return;
        } else {
            /**
             * user is logged in so update the timeout to reflect new activity
             */
            Session::updateTimeout();
        }
        /**
         * Build the controller based on the previously computed settings
         */
        if ($this->controller = ControllerFactory::buildController($this)) {

            $this->logger->info($this->route);
            $method = $this->route->getMethod();
            if (method_exists($this->controller, $method)) {
                /**
                 * Check if there is a parameter set from the request
                 */
                $this->logger->info($method);
                $data = $this->route->getData();
                if (empty($data)) {
                    $this->appOutput->appendBody($this->controller->$method());
                } else {
                    $this->logger->info($data);
                    $this->appOutput->appendBody($this->controller->$method($data));
                }
            } else {
                $this->logger->warning("No method found by name of " . $method . ' in the controller ' . $this->route->getControler());

                $this->appOutput->appendBody($this->view('errors/405'));
            }
            /**
             * If no controller could be created, it's because the class doesnt exists or is setup wrong
             * Show 404
             */
        } else {
            $this->logger->warning("No Controller found by name of " . $this->route->getControler());
            $this->appOutput->appendBody($this->view('errors/404'));
        }
    }

    /**
     * Applies theming, menus, modals, and styling
     *  to the controller output as a final preparation for the core
     */
    public function layout(): void
    {
        $this->layout = new Layout($this);
        //var_dump($this->request);
        if ($this->request->getType() == 'http') {
            $this->appOutput->setBody($this->layout->apply());
        }
    }

    /**
     * Set the php errror mode repective of the setting
     * in the webConfig.
     */

    private function setErrorMode(): void
    {
        if ($this->inDebugMode()) {
            enablePHPErrors();
        } else {
            disablePHPErrors();
        }
    }

    /**
     * Check if the application is currently in debug mode
     *
     * @return boolean
     */
    public function inDebugMode(): bool
    {

        if (AppDatabase::getDebugMode()) {
            return true;
        }
        return false;

    }

    /**
     * Get the application database ID
     *
     * @return int Always returns 1
     */
    public static function getID()
    {
        /**
         * We always return one because there is currently only one application running on this core.
         * It allows flexabillity if that ever changes.
         */
        return '1';
    }

}

?>