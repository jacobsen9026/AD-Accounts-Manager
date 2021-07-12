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

use App\App\App;
use App\Config\Theme;
use App\Models\Database\UserNotificationDatabase;
use System\App\Auth\CoreUser;
use App\App\Session;
use App\Models\Database\UserDatabase;
use System\App\UserLogger;
use App\Models\Database\PermissionMapDatabase;
use System\Lang;

class User extends CoreUser
{

    const FULL_NAME = "fullName";
    const USER = "user";

    /**
     *
     * @var array<PrivilegeLevel>
     */
    public $privilegeLevels;
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
    protected NotificationOptions $notificationOptions;
    protected $id;
    /**
     *
     * @var UserLogger
     */
    protected $logger;
    protected $email;

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
        parent::__construct();
        $this->username = $username;
        $this->logger = UserLogger::get();
        $this->notificationOptions = new NotificationOptions();
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
            $app->user->setEmail(UserDatabase::getEmail($app->user->username));
            $app->user->setId(UserDatabase::getID($app->user->username));
            $rawOptions = UserNotificationDatabase::getUserOptions($app->user->getId());
            $notificationOptions = new NotificationOptions();
            UserLogger::get()->debug($rawOptions);
            if ($rawOptions != null) {

                $notificationOptions->setUserChange($rawOptions['Notify_User_Change']);
                $notificationOptions->setUserDisable($rawOptions['Notify_User_Disable']);
                $notificationOptions->setUserCreate($rawOptions['Notify_User_Create']);
                $notificationOptions->setGroupChange($rawOptions['Notify_Group_Change']);
                $notificationOptions->setGroupCreate($rawOptions['Notify_Group_Create']);

            }
            $app->user->setNotificationOptions($notificationOptions);
        }

        //$app->logger->info($app->user);
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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotificationOptions()
    {
        return $this->notificationOptions;
    }

    /**
     * @param mixed $notificationOptions
     * @return User
     */
    public function setNotificationOptions($notificationOptions)
    {
        $this->notificationOptions = $notificationOptions;
        return $this;
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
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
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

    public function setSuperUser($superUser)
    {
        $this->superAdmin = $superUser;
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
    public function getPrivilegeLevels(): ?array
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
    public function setPrivilegeLevels($privilegeLevelArray): self
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
    public function save(): ?bool
    {

        try {
            if (($this->getApiToken() === null) || ($this->getApiToken() === '')) {
                $this->generateAPIToken();
            }
            $this->logger->debug("Changing theme to " . $this->theme);
            Session::setUser($this);
            //Cookie::set(self::USER . '_' . $this->username, \system\Encryption::encrypt(serialize($this)));
            UserDatabase::setUserToken($this->username, $this->apiToken);
            //var_dump($this->theme);
            UserDatabase::setUserTheme($this->username, $this->theme);
            UserDatabase::setUserEmail($this->username, $this->email);
            UserNotificationDatabase::setUserOption($this);
            /**
             * Dont save privilege if we're the local admin
             *
             * if ($this->username !== CoreUser::ADMINISTRATOR) {
             * UserDatabase::setUserPrivilege($this->username, $this->privilege);
             * }
             */
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }


}
