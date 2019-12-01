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
class Router {

    //put your code here
    public $module;
    public $page;
    public $action;
    private $app;

    /*
     *
     */

    public function __construct(Core $app) {
        $this->app = $app;
    }

    public function getRoute() {
        if (!isset($this->app->request->module)) {
            $this->getDefaultModule();
        } else {
            $this->module = $this->app->request->module;
        }
        if (!isset($this->app->request->page)) {
            $this->getDefaultPage();
        } else {
            $this->page = $this->app->request->page;
        }
    }

    /**
     *
     * @return type
     */
    public function module() {
        return $this->module;
    }

    /**
     *
     * @return type
     */
    public function page() {
        return $this->page;
    }

    /**
     *
     * @return type
     */
    public function action() {
        return $this->action;
    }

    public function getDefaultModule() {
        return "Home";
    }

    public function getDefaultPage() {
        return "index";
    }

}

?>
