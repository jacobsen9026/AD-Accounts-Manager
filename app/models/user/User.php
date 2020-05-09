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

namespace app\models\user;

/**
 * Description of User
 *
 * @author cjacobsen
 */
use system\app\auth\CoreUser;
use system\app\Session;
use system\app\App;
use system\app\AppLogger;
use app\models\database\UserDatabase;
use app\models\user\PrivilegeLevel;
use app\models\database\PermissionMapDatabase;
use app\models\database\PrivilegeLevelDatabase;

class User extends CoreUser {

    const FULL_NAME = "fullName";
    const USER = "user";

    /**
     *
     * @var array<PrivilegeLevel>
     */
    public $privilegeLevels;
    public $adGroupName;
    public $superUser = false;

    /**
     *
     * @var string
     */
    public $theme = \app\config\Theme::DEFAULT_THEME;

    /**
     *
     * @var string
     */
    public $fullName;

    /**
     *
     * @param string $username
     * @return self
     */
    function __construct(string $username = null) {
        //set_error_handler(array($this, 'handleError'));
        //set_exception_handler(array($this, 'handleException'));

        if ($username == self::ADMINISTRATOR) {
            $this->setAsAdministrator();
        } else {
            $this->privilege = Privilege::UNAUTHENTICATED;
        }
        $this->theme = \app\config\Theme::DEFAULT_THEME;
        \system\app\AppLogger::get()->debug($this->theme);
        return $this;

        //$this->save();
    }

    /**
     * Sets this user as the local administrator
     * @return self $this
     */
    private function setAsAdministrator() {
        $this->privilege = Privilege::TECH;
        $this->fullName = \system\Lang::get('Administrator Full Name');
        $this->username = "admin";
        $this->privilegeLevels = new PrivilegeLevel();
        $this->privilegeLevels->setSuperAdmin(true);
        return $this;
    }

    /**
     *
     * @return User
     */
    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get the users chosen theme
     * @return string
     */
    public function getTheme() {

        return $this->theme;
    }

    /**
     *
     * @return string
     */
    public function getFullName() {
        return $this->fullName;
    }

    /**
     *
     * @return array <Permissions>
     */
    public function getPermissions(string $ou) {
        $levelIDs = array();
        foreach ($this->privilegeLevels as $level) {
            /* @var $level PrivilegeLevel */
            $levelIDs[] = $level->getId();
        }
        $permissions = PermissionMapDatabase::getRelevantPermissions($levelIDs, $ou);

        return $permissions;
    }

    /**
     *
     * @param type $theme
     * @return User
     */
    public function setTheme(string $theme) {
        \system\app\AppLogger::get()->debug("Changing theme to " . $theme);
        $this->theme = $theme;

        // $this->save();
        return $this;
    }

    /**
     *
     * @param string $fullName
     * @return User
     */
    public function setFullName(string $fullName) {
        $this->fullName = $fullName;
        return $this;
    }

    public function setSuperUser($superUser) {
        $this->superUser = $superUser;
        return $this;
    }

    /**
     *
     * @param string $token
     * @return $this
     */
    public function setToken(string $token) {
        $this->apiToken = $token;
        return $this;
    }

    public function addPrivilegeLevel(PrivilegeLevel $privilegeLevel) {
        $this->privilegeLevels[] = $privilegeLevel;
        return $this;
    }

    public function getPrivilegeLevels() {
        return $this->privilegeLevels;
    }

    /**
     *
     * @param type $privilegeLevelArray
     * @return $this
     */
    public function setPrivilegeLevels($privilegeLevelArray) {
        $this->privilegeLevels = $privilegeLevelArray;
        return $this;
    }

    public function loadPrivilegeLevel($adGroupName) {
        AppLogger::get()->debug($adGroupName);

        $this->addPrivilegeLevel(PrivilegeLevelDatabase::getPrivilegeLevel($adGroupName));
        return $this;
    }

    /**
     * Save this user to the database
     *
     */
    public function save() {
        try {
            if ($this->getApiToken() == null or $this->getApiToken() == '')
                $this->generateAPIToken();
            AppLogger::get()->debug("Changing theme to " . $this->theme);
            Session::setUser($this);
            //Cookie::set(self::USER . '_' . $this->username, \system\Encryption::encrypt(serialize($this)));
            UserDatabase::setUserToken($this->username, $this->apiToken);
            //var_dump($this->theme);
            UserDatabase::setUserTheme($this->username, $this->theme);
            UserDatabase::setUserPrivilege($this->username, $this->privilege);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * Loads user data from Session and Database into the App instance
     * @param App $app
     */
    public static function load(App $app) {
        $app->user = Session::getUser();
        if ($app->user != null and $app->user->username != null) {
            $app->user->setToken(UserDatabase::getToken($app->user->username));
            $app->user->setTheme(UserDatabase::getTheme($app->user->username));
        }

        $app->logger->info($app->user);
    }

    //put your code here
}
