<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system;

/**
 * Description of Get
 *
 * @author cjacobsen
 */
class CorePost {

    //put your code here
    public $uri;

    public function __construct(Request $request) {
        $this->uri = $request->uri;
    }

    public function getGet($key) {
        if (isset($_POST[$key]) and $_POST[$key] != NULL) {
            return $_POST[$key];
        }
    }

}
