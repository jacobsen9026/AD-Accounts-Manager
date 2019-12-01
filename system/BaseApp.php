<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jacobsen\system;

/**
 * Description of App
 *
 * @author cjacobsen
 */
use jacobsen\system\Core;

class BaseApp {

    //put your code here
    public $output;
    public $core;

    function __construct(Core $core) {
        $this->core = $core;
    }

    public function debug($string) {
        $this->core->debug($string);
    }

}

?>
