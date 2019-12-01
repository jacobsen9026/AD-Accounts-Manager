<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jacobsen\app;

/**
 * Description of App
 *
 * @author cjacobsen
 */
use jacobsen\system\Core;
use jacobsen\system\CoreApp;

class App extends CoreApp {

    function __construct(Core $core) {
        parent::__construct($core);
        $this->router = new config\Router($this);

        $this->config = new config\Config($this);
    }

    public function start() {
        $this->run();
    }

    //put your code here
}

?>