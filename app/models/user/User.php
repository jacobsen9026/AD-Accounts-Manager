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

namespace App\Models\User;

/**
 * Description of user
 *
 * @author cjacobsen
 */

use app\config\Theme;
use System\App\AppLogger;
use System\App\Auth\CoreUser;
use System\App\Session;
use System\App\App;
use App\Models\Database\UserDatabase;
use System\App\UserLogger;
use App\Models\Database\PermissionMapDatabase;
use App\Models\Database\PrivilegeLevelDatabase;
use system\Lang;

class User extends CoreUser
{

    const FULL_NAME = "fullName";
    const USER = "user";

    /**
     *
     * @var array<PrivilegeLevel>
     */
    public $privilegeLevels;
    public $adGroupName;
    public $superAdmin = false;


    /**
     *
     * @var string
     */
    public $theme = Theme::DEFAULT_THEME;

    /**
     *
     * @var string
     */
    public $fullName;

    /**
     *
     * @var UserLogger
     */
    protected $logger;

    /**
     * Creates a new web User. Use the constant ADMINISTRATOR to set
     * what the local administrator username should be
     *
     * @param string $username
     *
     * @return self
     */
    public function __construct(string $username = null)
    {
        $this->logger = UserLogger::get();
        if ($username == self::ADMINISTRATOR) {
            $this->setAsAdministrator();
        }
        $this->theme = Theme::DEFAULT_THEME;
        $this->logger->debug($this->theme);

    }

    /**
     * Sets this user as the local administrator
     *
     * @return self $this
     */
    private function setAsAdministrator()
    {

        $this->fullName = Lang::get('Administrator Full Name');
        $this->username = "admin";
        $privilegeLevel = new PrivilegeLevel();
        $privilegeLevel->setId(-1);
        $privilegeLevel->setSuperAdmin(true);
        $this->authenticated(true);
        $this->superAdmin = true;
        $this->privilegeLevels = [$privilegeLevel];
        $this->logger->debug($this->privilegeLevels);

        return $this;
    }

    /**
     *
     * @return User
     */
    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get the users chosen theme
     *
     * @return string
     */
    public function getTheme()
    {

        return $this->theme;
    }

    /**
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     *
     * @return array <Permissions>
     */
    public function getPermissions(string $ou)
    {
        $levelIDs = [];
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
     *
     * @return User
     */
    public function setTheme(string $theme)
    {
        $this->logger->debug("Changing theme to " . $theme);
        $this->theme = $theme;

        // $this->save();
        return $this;
    }

    /**
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName(string $fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function setSuperUser($superUser)
    {
        $this->superAdmin = $superUser;
        return $this;
    }

    /**
     *
     * @param string $token
     *
     * @return $this
     */
    public function setToken(string $token)
    {
        $this->apiToken = $token;
        return $this;
    }


    public function addPrivilegeLevel(PrivilegeLevel $privilegeLevel)
    {

        $this->logger->info('Adding privilege: ' . $privilegeLevel->getAdGroup());
        $this->logger->debug($privilegeLevel);
        $this->privilegeLevels[] = $privilegeLevel;
        $this->logger->debug($this->privilegeLevels);
        return $this;
    }


    /**
     * @return array
     */
    public function getPrivilegeLevels()
    {
        $this->logger->debug($this->privilegeLevels);
        return $this->privilegeLevels;
    }


    /**
     *
     * @param type $privilegeLevelArray
     *
     * @return $this
     */
    public function setPrivilegeLevels($privilegeLevelArray)
    {
        if (!is_array($privilegeLevelArray)) {
            $privilegeLevelArray = [$privilegeLevelArray];
        }
        $this->privilegeLevels = $privilegeLevelArray;
        return $this;
    }


    /**
     * Save this user to the database
     *
     */
    public function save()
    {
        try {
            if ($this->getApiToken() == null or $this->getApiToken() == '')
                $this->generateAPIToken();
            $this->logger->debug("Changing theme to " . $this->theme);
            Session::setUser($this);
            //Cookie::set(self::USER . '_' . $this->username, \system\Encryption::encrypt(serialize($this)));
            UserDatabase::setUserToken($this->username, $this->apiToken);
            //var_dump($this->theme);
            UserDatabase::setUserTheme($this->username, $this->theme);
            if ($this->username != CoreUser::ADMINISTRATOR) {
                UserDatabase::setUserPrivilege($this->username, $this->privilege);
            }
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * Loads user data from Session and Database into the App instance
     *
     * @param App $app
     */
    public static function load(App $app)
    {
        $app->user = Session::getUser();
        if ($app->user != null and $app->user->username != null) {
            $app->user->setToken(UserDatabase::getToken($app->user->username));
            $app->user->setTheme(UserDatabase::getTheme($app->user->username));
        }

        $app->logger->info($app->user);
    }


}
