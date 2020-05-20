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

namespace System\App;

/**
 * Description of Router
 *
 * @author cjacobsen
 */
use System\App\App;
use System\Post;
use System\Get;
use System\App\Route;

/**
 * @name CoreRouter
 *
 */
class Router {

    private $logger;

    /** @var array|null */
    public $customRoutes = null;
    private $request = null;

    /**
     *
     * @var Route
     */
    private $route;

    public function __construct(App $app) {
        /**
         * We want to pull in the application
         * logger so we can write to it's logs
         */
        $this->logger = $app->logger;
        /**
         * We need to instantiate the Route object
         * so we can use it later
         */
        $this->route = new Route();
        /**
         * Lastly we need the request that the user
         * made to the application
         */
        $this->request = $app->request;

        $this->includeCustomRoutes();
    }

    /**
     *
     * @return string
     */
    public static function getDefaultController() {
        return "Home";
    }

    /**
     *
     * @return string
     */
    public static function getDefaultMethod() {
        return "index";
    }

    /**
     *
     * @return array
     */
    public function route() {
        /**
         * Route the request
         */
        $this->setRoute();


        /*
         * return the response
         */
        $this->replaceCustomRoutes();
        //$route = $this->replaceCustomRoutes($route);
        $this->logger->debug($this->route);
        return $this->route;
    }

    /**
     * Replaces custom routes
     */
    private function replaceCustomRoutes() {
//Inset custom routes over computed route
        $controller = $this->route->getControler();
        $method = $this->route->getMethod();
        foreach ($this->customRoutes as $customRoute) {
            if ($customRoute[0] == $controller) {
                $this->route->setControler($customRoute[2]);
            }
        }
    }

    private function includeCustomRoutes() {
        require(CONFIGPATH . DIRECTORY_SEPARATOR . "Routes.php");
    }

    /**
     *
     * @param Route $route
     */
    private function setRouteFromRequest(Route $route) {
        /**
         * First we pull the uri from the request supplied when the router
         * was instantiated.
         */
        $uri = $this->request->uri;
        $this->logger->debug($uri);
        /*
         * Break up the request by slashes into /controller/method/data
         */
        $exploded = explode("/", $uri, 4);
        //var_export($exploded);
        if (sizeof($exploded) > 0) {
            if (isset($exploded[1]) and $exploded[1] != '') {
                $route->setControler(ucfirst(strtolower($exploded[1])));
            }
            if (isset($exploded[2]))
                $this->logger->debug($exploded[2]);
            if (isset($exploded[2]) and $exploded[2] != '') {
                $route->setMethod($exploded[2]);
            }
            if (isset($exploded[3]) and $exploded[3] != '') {
                $route->setData($exploded[3]);
            }
        }
        /**
         * Let's log what the initial generated route is so far
         */
        $this->logger->debug("Request made: " . $this->route->getControler() . "->" . $this->route->getMethod() . "->" . $this->route->getData());
    }

    /**
     * Set the route to take based on the request
     */
    private function setRoute() {
        /**
         *  First we build a route based on the request
         */
        $this->setRouteFromRequest($this->route);

        /**
         * If anything is missing we need to fill in the defaults
         * We start with the controller
         */
        if (is_null($this->route->getControler())) {
            $this->route->setControler($this->getDefaultController());
        } else {
            $this->route->setControler($this->preProcessController($this->route->getControler()));
        }
        /**
         * And finish with the method
         */
        if (is_null($this->route->getMethod())) {
            $this->route->setMethod($this->getDefaultMethod());
        } else {

            $this->route->setMethod($this->preProcess($this->route->getMethod()));
        }
        $this->handleAPIRequests();
        /**
         * Similar to the api we handle settings controllers the same way
         */
        if (strtolower($this->route->getControler()) == "settings") {
            $this->route->unfoldLeft();
            $this->route->setControler("settings\\" . $this->route->getControler());
        }

        /**
         * Lastly we check if post was set and append Post to the method name so we call
         * a method that knows to expect post data. If no post is set check the get and
         * do the same. Otherwise do nothing to the route.
         */
        if (Post::isSet()) {
            $this->route->setMethod($this->route->getMethod() . 'Post');
        } elseif (Get::isSet()) {
            $this->route->setMethod($this->route->getMethod() . 'Get');
        }
        /**
         * Let's log the final route we will take
         */
        $this->logger->info("Route taken: " . $this->route->getControler() . "->" . $this->route->getMethod() . "->" . $this->route->getData());
    }

    /**
     *
     * @param type $string
     * @return type
     */
    private function preProcess($string) {
        /*
         * Break down a request like /students-cms/account-status
         * and convert it to a call to the method accountStatus
         * on an object of the class StudentsCms
         */
//$this->app->logger->debug($string . '  ' . strpos($string, '-'));
        if (strpos($string, '-')) {

            $this->logger->debug('- found');
            $first = true;
            foreach (explode("-", $string) as $piece) {
                if ($first) {

                    $this->logger->debug('first piece ' . $piece);
                    $processedString = $piece;
                    $first = false;
                    continue;
                }
                $processedString .= ucfirst($piece);

                $this->logger->debug($processedString);
            }
            return $processedString;
//$this->app->logger->debug($piece);
//return $piece;
        }
        return $string;
    }

    /**
     * Shifts controller, method and data, left one function, removing the controller.
     * @deprecated since version number
     * */
    private function shiftLeft($route) {
        if (!is_null($this->method)) {
            $this->controller = $this->method;
            $this->method = $this->data;
            if (is_null($this->method)) {
                $this->method = $this->getDefaultMethod();
            }
        }
    }

    /**
     *
     * @param type $string
     * @return type
     */
    private function preProcessController($string) {

        $this->controller = $string;

        return $this->controller;
    }

    private function handleAPIRequests() {
        /**
         * We need to handle api calls going to the /api/... uri
         * All those controllers are under the same directory so
         * we are actually looking for controller and method and
         * data in the wrong spot. They are all shifted right one.
         *
         */
        if (strtolower($this->route->getControler()) == "api") {
            /**
             * First we shift everything left, method->controller data->method
             * while maintaining the integrity of the data
             */
            $this->route->unfoldLeft();
            /**
             * We tell PHP where the controller is by appending the controller
             * api directory to it.
             */
            $this->route->setControler("api\\" . $this->route->getControler());
            $this->logger->debug($this->route->getControler());
            $this->logger->debug($this->route->getMethod());
            $this->logger->debug($this->route->getData());

            /**
             * There is currently no settings api so I don't think this is needed
             *
             * /
             *
             */
            if (strtolower($this->route->getControler()) == "api\\settings") {
                $this->route->unfoldLeft();
                $this->route->setControler("api\\settings\\" . $this->route->getControler());

                $this->logger->debug($this->route->getControler());
                $this->logger->debug($this->route->getMethod());
                $this->logger->debug($this->route->getData());
                //var_dump($this->route);
            }
            /**
             */
        }
    }

}

?>
