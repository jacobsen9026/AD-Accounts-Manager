<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app;

/**
 * Description of Get
 *
 * @author cjacobsen
 */
class CoreGet {

    //put your code here
    private $uri;

    public function __construct(Request $request) {
        $this->uri = $request->uri;
    }

    public function get($key) {
        if (isset($_GET[$key]) and $_GET[$key] != NULL) {
            return $_GET[$key];
        }
    }

    public function set($key) {

    }

    public function remove($key) {

    }

}
