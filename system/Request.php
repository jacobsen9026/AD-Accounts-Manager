<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system;

/**
 * Description of Request
 *
 * @author cjacobsen
 */
class Request {

    /**
     *
     * @var string
     */
    public $post = null;
    public $get = null;
    public $uri = null;
    public $module = null;
    public $page = null;
    public $action = null;
    public $core;

    /**
     *
     * @param \SAM\App $core
     */
    function __construct(Core $core) {
        $this->core = $core;
        if (isset($_GET)) {
            $this->get = $_GET;
        }
        if (isset($_POST)) {
            $this->post = $_POST;
        }
        //var_export($_SERVER);
        if (isset($_SERVER["REQUEST_URI"])) {
            $this->uri = $_SERVER["REQUEST_URI"];
            $exploded = explode("/", $this->uri);
            //var_export($exploded);
            if (sizeof($exploded) > 0) {
                if (isset($exploded[1]) and $exploded[1] != '') {
                    $this->module = $exploded[1];
                    //echo "module";
                }
                //echo $this->module;
                if (isset($exploded[2]) and $exploded[2] != '') {
                    $this->page = $exploded[2];
                }
                if (isset($exploded[3]) and $exploded[3] != '') {
                    $this->action = $exploded[3];
                }
            }
            $this->core->logger->debug("Request made: " . $this->module . "->" . $this->page . "->" . $this->action);
        }
        //var_export($this);
        //return $this;
    }

    public function post(): string {
        return $this->post;
    }

    public function get() {
        return $this->get;
    }

    public function uri() {
        return $this->uri;
    }

    public function module() {
        return $this->module;
    }

    public function page() {
        return $this->page;
    }

    public function action() {
        return $this->action;
    }

    public function setPost(string $post) {
        $this->post = $post;
    }

    public function setModule($module) {
        $this->module = $module;
    }

    public function setPage($page) {
        $this->page = $page;
    }

    public function setAction($action) {
        $this->action = $action;
    }

}
