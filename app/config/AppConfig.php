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

    protected $name = "School Accounts Manager";
    protected $forceHTTPS = false;
    protected $timeout = 1200;
    protected $admins;

    function __construct(array $keyValuePairs = null) {
        parent::__construct($keyValuePairs);
    }

    function getName() {
        return $this->name;
    }

    function getForceHTTPS() {
        return $this->forceHTTPS;
    }

    function getTimeout() {
        return $this->timeout;
    }

    function getAdmins() {
        return $this->admins;
    }

}
