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
    public $referer = null;
    public $action = null;
    public $core;

    /**
     *
     * @param \SAM\App $core
     */
    function __construct(Core $core) {
        $this->core = $core;
        /*
         * Store GET
         */
        if (isset($_GET)) {
            $this->get = $_GET;
        }
        /*
         * Store POST
         */
        if (isset($_POST)) {
            $this->post = $_POST;
        }
        /*
         * Store the referer
         */
        if (isset($_SERVER['HTTP_REFERER'])) {
            $this->referer = $_SERVER['HTTP_REFERER'];
        }
        /*
         * Check that URI is set
         */
        if (isset($_SERVER["REQUEST_URI"])) {
            /*
             * Set Request URI
             */
            $this->uri = $_SERVER["REQUEST_URI"];
            /*
             * Break up the request by slashes into /module/page/action
             */
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

}
