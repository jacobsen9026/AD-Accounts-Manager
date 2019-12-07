<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\config;

/**
 * Description of AppConfig
 *
 * @author cjacobsen
 */
use system\app\CoreConfig;

class AppConfig extends CoreConfig {

    //put your code here
    private $name;
    private $forceHTTPS;
    private $timeout;
    private $admins;

    function __construct() {
        parent::__construct();
        $this->name = "School Accounts Manager";
        $this->forceHTTPS = false;
        $this->timeout = 1200;
    }

}
