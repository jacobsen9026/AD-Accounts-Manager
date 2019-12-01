<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jacobsen\system;

/**
 * Description of Controller
 *
 * @author cjacobsen
 */
use jacobsen\app\App;

class CoreController {

    public $app;
    public $output;
    public $layoutName;

    //put your code here
    function __construct(App $app) {
        $this->app = $app;
    }

    private function add($string) {
        $this->output .= $string;
    }

}

?>
