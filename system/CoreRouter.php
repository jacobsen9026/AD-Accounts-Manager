<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jacobsen\system;

/**
 * Description of Router
 *
 * @author cjacobsen
 */
use jacobsen\app\App;

/**
 * @name CoreRouter
 *
 */
class CoreRouter {

    //put your code here
    public $module = null;
    public $page = null;
    public $action = null;
    private $request = null;
    private $app = null;

    public function __construct(App $app) {
        $this->app = $app;
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

    public function route() {
        /**
         * Route the request
         *
         * Call the router and the appropriate class and method to execute
         *
         * Create instance of this class
         *
         * Execute method
         *
         * Pass parameter if set
         */
        $this->getRoute();
        $route = array($this->module, $this->page);
        if (isset($this->action) and $this->action != '') {
            $route[] = $this->action;
        }
        return $route;
    }

    private function getRoute() {
        if (!isset($this->request->module)) {
            $this->module = $this->getDefaultModule();
        } else {
            $this->module = $this->request->module;
        }
        if (!isset($this->request->page)) {
            $this->page = $this->getDefaultPage();
        } else {
            $this->page = $this->request->page;
        }
        if (isset($this->request->action)) {
            $this->action = $this->request->action;
        }
        //var_dump($this);
        $this->app->core->debug("Route taken: " . $this->module . "->" . $this->page . "->" . $this->action . "<br/>");
    }

}

?>
