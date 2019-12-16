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

namespace system\common;

/**
 * Description of Router
 *
 * @author cjacobsen
 */
use system\app\App;
use system\CoreException;

/**
 * @name CoreRouter
 *
 */
class CoreRouter {

    private $logger;

    /** @var array|null */
    public $customRoutes = null;
    private $app = null;
    private $userPrivilege = null;
    private $request = null;
    public $controller = null;
    public $method = null;
    public $data = null;

    public function __construct(App $app) {
        $this->app = $app;
        $this->logger = $app->logger;

        $this->request = $app->request;
    }

    /**
     *
     * @return string
     */
    public function getDefaultModule() {
        return "Home";
    }

    /**
     *
     * @return string
     */
    public function getDefaultPage() {
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
         * Prepare the response
         */
        $route = array($this->controller, $this->method);
        if (isset($this->data) and $this->data != '') {
            $route[] = $this->data;
        }

        /*
         * return the response
         */
        $route = $this->replaceCustomRoutes($route);
        return $route;
    }

    private function replaceCustomRoutes($route) {
        //Inset custom routes over computed route
        //var_dump($route);
        $controller = $route[0];
        $method = $route[1];
        foreach ($this->customRoutes as $customRoute) {
            if ($customRoute[0] == $controller) {
                $route[0] = $customRoute[2];
                /*
                  if ($customRoute[1] != '*') {
                  $route[1] = $customRoute[3];
                  }
                 */
            }
        }
        //var_dump($route);
        return $route;
    }

    private function setRoute() {
        //Set the route to take based on the request
        //This is prior to custom route insertion
        if (!isset($this->request->module)) {
            $this->controller = $this->getDefaultModule();
        } else {
            $this->controller = $this->preProcess($this->request->module);
        }
        if (!isset($this->request->page)) {
            $this->method = $this->getDefaultPage();
        } else {

            $this->method = $this->preProcess($this->request->page);
        }
        if (isset($this->request->action)) {
            $this->data = $this->request->action;
        }
        if (isset($_POST) and $_POST != null) {
            $this->method = $this->method . 'Post';
        } elseif (isset($_GET) and $_GET != null) {
            $this->method = $this->method . 'Get';
        }
        //var_dump($this);
        $this->logger->info("Route taken: " . $this->controller . "->" . $this->method . "->" . $this->data);
    }

    private function preProcess($string) {
        /*
         * Break down a request like /students-cms/account-status
         * and convert it to a call to the method accountStatus
         * on an object of the class StudentsCms
         */
        //$this->app->logger->debug($string . '  ' . strpos($string, '-'));
        if (strpos($string, '-')) {

            //$this->app->logger->debug('- found');
            $brokenString = explode("-", $string);
            $first = true;
            foreach ($brokenString as $piece) {
                if ($first) {

                    //$this->app->logger->debug('first piece ' . $piece);
                    $processedString = $piece;
                    $first = false;
                    continue;
                }
                $processedString .= ucfirst($piece);

                //$this->app->logger->debug($processedString);
                return $processedString;
            }

            //$this->app->logger->debug($piece);
            return $piece;
        }
        return $string;
    }

}

?>
