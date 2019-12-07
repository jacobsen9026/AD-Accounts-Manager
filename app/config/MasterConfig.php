<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\config;

/**
 * Description of App
 *
 * @author cjacobsen
 */
use system\app\CoreConfig;
use app\App;
use app\AppLogger;

class MasterConfig extends CoreConfig {

    //put your code here

    public $appConfig;
    public $emailConfig;
    public $webConfig;
    public $authConfig;
    public $logger;

    function __construct() {
        parent::__construct();

        $this->appConfig = new AppConfig();
        $this->emailConfig = new EmailConfig();
        $this->webConfig = new WebConfig();
        $this->authConfig = new AuthConfig();

        //$this->logger = $logger;
        //$this->load($this->app->logger);
    }

}

?>
