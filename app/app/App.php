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
 *
 */

use App\Controllers\Controller;
use App\Controllers\Login;
use Exception;
use System\App\AppLogger;
use System\App\Layout;
use System\App\LDAPLogger;
use System\App\RequestRedirection;
use System\App\Error\AppErrorHandler;
use System\App\UserLogger;
use System\App\ControllerFactory;
use System\Core;
use system\Header;
use System\Request;
use System\App\Router;
use App\Models\User\User;
use App\Models\Database\AppDatabase;
use System\App\AppOutput;
use System\App\Interfaces\AppInterface;
use System\Common\CommonApp;
use System\App\WindowsLogger;
use System\SystemLogger;
use System\Traits\Parser;


class App extends CommonApp implements AppInterface
{

    use RequestRedirection;
    use Parser;

    public static $version = '0.1.6';

    /** @var App */
    public static App $instance;
    /** @var AppLogger|null The system logger */
    public ?AppLogger $logger;

    /** @var Controller */
    public Controller $controller;

    /** @var User|null The web user object */
    public ?User $user;
    /** @var SystemLogger|null The system logger */
    private $coreLogger;

    /**
     *
     */
    public function __construct()
    {

        Header::allowServiceWorker('/');
        self::$instance = $this;
        /**
         * Trigger the appErrorHandler to begin until we load the config
         * Start up the coreLogger to be used only by the config
         */
        new AppErrorHandler();
        $this->coreLogger = SystemLogger::get();


        $this->request = Request::get();
        /**
         * Set up the appLogger
         */
        $this->logger = new AppLogger;
        $this->coreLogger->info("The app logger has been created");

        /**
         * Load the Config Database
         */
        $configDSN = "sqlite:" . APPCONFIGDBPATH;
        new ConfigDatabase($configDSN);

        /**
         * Load the request into the app
         */
        $this->loadConfig();
    }

    /**
     * Load the application configuration
     */
    public function loadConfig(): void
    {
        $this->coreLogger->info("The app config has been loaded");
        define('GAMPATH', CONFIGPATH . DIRECTORY_SEPARATOR . "google");

        /**
         * Set the php error mode
         */
        $this->setErrorMode();
    }

    /**
     * Set the php error mode respective of the setting
     * in the webConfig.
     */

    private function setErrorMode(): void
    {
        if (self::inDebugMode()) {
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
    public static function inDebugMode(): bool
    {

        return Core::inDebugMode();

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

    /**
     * Run the application
     *
     * @return AppOutput
     */
    public function run(): AppOutput
    {

        $this->appOutput = new AppOutput();
        $this->logger->info("Creating Session");
        User::load($this);
        try {
            $this->route();
            /**
             * This is where the magic happens. The control function calls the class and method
             * determined by the routing.
             */
            $this->control();
        } catch (Exception $ex) {
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
     */
    private function handleHttpsRedirect(): void
    {
        $this->logger->info("Protocol: " . $this->request->getProtocol());
        $this->logger->info("Hostname: " . ($this->request->getServerName()));
        if ($this->request->getProtocol() === "http" && AppDatabase::getForceHTTPS()) {
            $this->redirect("https://" . $this->request->getServerName() . $this->request->getUri());
        }
    }

    /**
     * Checks if request should be redirected to a different FQDN based on app settings
     *
     */
    private function handleHostnameRedirect(): void
    {
        $this->logger->info("Hostname: " . ($this->request->getServerName()));
        if (strtolower($this->request->getServerName()) !== strtolower(AppDatabase::getWebsiteFQDN())) {
            if (AppDatabase::getWebsiteFQDN() != "") {
                $this->redirect($this->request->getProtocol() . "://" . strtolower(AppDatabase::getWebsiteFQDN()) . $this->request->getUri());
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
        $this->appOutput = new AppOutput();
        /*
         * Check that the user is logged on and if not, set the route to the login screen
         */
        if ($this->route->getControler() != 'Logout' && $this->route->getControler() != 'Img' && $this->route->getControler() != 'Mobile' && $this->route->getControler() != 'Js') {
            if (!isset($this->user) or $this->user === null or $this->user->authenticated === false) {
                $this->logger->warning('user not logged in');
                // Change route to login if not logged in.
                $this->controller = new Login($this);
                $this->appOutput->appendBody($this->controller->index());
                return;
            }
        }

        /**
         * user is logged in so update the timeout to reflect new activity
         */
        Session::updateTimeout();
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
        if ($this->request->getType() === 'http') {
            $this->appOutput->setBody($this->layout->apply());
        }
    }

}

?>