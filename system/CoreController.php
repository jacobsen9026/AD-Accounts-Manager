<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system;

/**
 * Description of Controller
 *
 * @author cjacobsen
 */
use app\App;

class CoreController extends Parser {

    public $app;
    public $content;
    public $layout;

    //put your code here
    function __construct(App $app) {
        $this->app = $app;
    }

    private function add($string) {
        $this->content .= $string;
    }

}

?>