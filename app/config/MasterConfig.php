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


    public $savedConfigs = array('app', 'msad', 'gam', 'email', 'web', 'auth', 'district');

    /** @var AppConfig|null */
    public $app;

    /** @var ADConfig|null */
    public $msad;

    /** @var GAMConfig|null */
    public $gam;

    /** @var EmailConfig|null */
    public $email;

    /** @var WebConfig|null */
    public $web;

    /** @var AuthConfig|null */
    public $auth;

    /** @var AppLogger|null */
    public $logger;

    /** @var DistrictConfig|null */
    public $district;

    /** @var string|null */
    public $configFilePath;

    function __construct() {
        parent::__construct();
        $this->configFilePath = CONFIGPATH . DIRECTORY_SEPARATOR . 'store' . DIRECTORY_SEPARATOR;
        $this->logger = AppLogger::get();
        $this->createNew();
        $this->loadConfig();
    }

    public function createNew() {
        $this->app = new AppConfig();
        $this->msad = new ADConfig();
        $this->email = new EmailConfig();
        $this->web = new WebConfig();

        $this->gam = new GAMConfig();
        $this->auth = new AuthConfig();
        $this->district = new DistrictSettings();
    }

    public function loadConfig() {

        //var_dump($this);
        if (file_exists($this->configFilePath)) {


            //var_dump(get_object_vars($this));
            foreach (get_object_vars($this) as $name => $var) {
                if (file_exists($this->configFilePath . $name . '.cfg')) {
                    $class = get_class($var);
                    $settings = array();
                    $settings = unserialize(file_get_contents($this->configFilePath . $name . '.cfg'));
                    $this->$name = new $class($settings);
                }
            }
            //var_dump($this);

            $this->logger->info('Loading App Config at ' . $this->configFilePath);
        }
        $this->logger->info("The app config has been loaded");
        /*
         * Set the php errror mode repective of the setting
         * in the webConfig.
         */
    }

    public function saveConfig() {
        $this->logger->info("Saving Config at " . $this->configFilePath);

        //$this->app->logger = null;
        //$configOutput = json_encode($this->config->app->getSettings());
        //file_put_contents($this->configFilePath, $configOutput);
        foreach (get_object_vars($this) as $name => $var) {
            if (in_array($name, $this->savedConfigs)) {
                // var_dump($var);
                file_put_contents($this->configFilePath . $name . '.cfg', serialize($var->getSettings()));
                //var_dump($var);
            }
        }
    }

}

?>
