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
    public $get = null;
    public $uri = null;
    public $module = null;
    public $page = null;
    public $referer = null;
    public $action = null;
    private $logger;

    /**
     *
     * @param \SAM\App $core
     */
    function __construct() {
        $this->logger = SystemLogger::get();
        /*
         * Store GET
         */
        if (isset($_GET)) {
            $this->get = $_GET;
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
                    $this->module = ucfirst(strtolower($exploded[1]));
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
            $this->logger->debug("Request made: " . $this->module . "->" . $this->page . "->" . $this->action);
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
