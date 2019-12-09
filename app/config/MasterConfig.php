<?php

/*
 * The MIT License
 *
 * Copyright 2019 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
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


    public $savedConfigs = array('app', 'msad', 'gam', 'email', 'web', 'auth', 'district', 'admin', 'notification');

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

    /** @var AdminConfig|null */
    public $admin;

    /** @var NotificationConfig|null */
    public $notification;

    /** @var string|null */
    public $configFilePath;

    /** @var MasterConfig|null */
    public static $instance;

    /**
     *
     * @return type
     */
    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct() {
        parent::__construct();
        self::$instance = $this;
        //Set the config file path
        $this->configFilePath = CONFIGPATH . DIRECTORY_SEPARATOR . 'store' . DIRECTORY_SEPARATOR;
        //Grab the appLogger instance for logging
        $this->logger = AppLogger::get();
        //Load default config
        $this->createNew();
        //Attempt load of saved config if it exists
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

        if (file_exists($this->configFilePath)) {
            $this->logger->info('Loading App Config');
            foreach (get_object_vars($this) as $name => $var) {
                if (file_exists($this->configFilePath . $name . '.cfg')) {
                    $class = get_class($var);
                    $settings = array();
                    $settings = unserialize(file_get_contents($this->configFilePath . $name . '.cfg'));
                    if (is_array($settings)) {
                        $this->$name = new $class($settings);
                        $this->logger->info('Loaded ' . $name . '.cfg into app');
                    } else {
                        $this->logger->error('Failed to load ' . $name . '.cfg');
                    }
                }
            }
        }
        $this->logger->info("The app config has been loaded");
    }

    public function saveConfig() {
        $this->logger->info("Saving App Config");
        foreach (get_object_vars($this) as $name => $var) {
            if (in_array($name, $this->savedConfigs)) {
                file_put_contents($this->configFilePath . $name . '.cfg', serialize($var->getSettings()));
                $this->logger->info('Saved ' . $name . '.cfg to store directory');
            }
        }
        $this->logger->info("The app config has been saved");
    }

}

?>
