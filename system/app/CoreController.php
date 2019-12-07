<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app;

/**
 * Description of Controller
 *
 * @author cjacobsen
 */
use system\Parser;
use app\App;
use system\CoreApp;

class CoreController extends Parser {

    /** @var App|null The view parser */
    public $app;
    public $config;
    public $content;
    public $layout;

    //put your code here
    function __construct($app) {
        $this->app = $app;
        $this->config = $app->config;
    }

    private function add($string) {
        $this->content .= $string;
    }

}

?>
