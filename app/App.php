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
use jacobsen\system\BaseApp;

class App extends BaseApp {

    //put your code here
    public function run() {
        $this->debug("debug test");
        return "Success";
    }

}

?>