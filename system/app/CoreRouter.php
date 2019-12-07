<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app;

/**
 * Description of Router
 *
 * @author cjacobsen
 */
use app\App;

/**
 * @name CoreRouter
 *
 */
class CoreRouter {

    private $logger;
    private $app = null;
    private $userPrivilege = null;
    private $request = null;
    public $module = null;
    public $page = null;
    public $action = null;
    public $data = null;

    public function __construct(App $app) {
        $this->app = $app;
        $this->logger = $app->logger;
        if ($this->app->user != null) {
            //$this->userPrivilege = $app->user->privilege;
        } else {
            throw new CoreException('The user privilege object was not found');
        }
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
        if (isset($this->data)) {
            $route[] = $this->data;
        }
        return $route;
    }

    private function getRoute() {
        if (!isset($this->request->module)) {
            $this->module = $this->getDefaultModule();
        } else {
            $this->module = $this->preProcess($this->request->module);
        }
        if (!isset($this->request->page)) {
            $this->page = $this->getDefaultPage();
        } else {

            $this->page = $this->preProcess($this->request->page);
        }
        if (isset($this->request->action)) {
            $this->action = $this->request->action;
        }
        //var_dump($this);
        $this->logger->info("Route taken: " . $this->module . "->" . $this->page . "->" . $this->action);
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