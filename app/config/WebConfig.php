<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\config;

/**
 * Description of WebConfig
 *
 * @author cjacobsen
 */
use system\CoreConfig;

class WebConfig extends CoreConfig {

    //put your code here
    private $debug = true;
    private $adminPassword;

    function getDebug() {
        return $this->debug;
    }

    function getAdminPassword() {
        return $this->adminPassword;
    }

    function setDebug($debug) {
        $this->debug = $debug;
    }

    function setAdminPassword($adminPassword) {
        $this->adminPassword = $adminPassword;
    }

}
