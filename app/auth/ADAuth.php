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

namespace App\Auth;

/**
 * Description of LDAP
 *
 * @author cjacobsen
 */

use App\Api\Ad\ADConnection;
use App\Api\Ad\ADUsers;
use App\Models\Database\DomainDatabase;
use App\Models\User\PrivilegeLevel;
use App\Models\User\User;
use System\App\Auth\AuthException;
use App\Api\AD;
use App\Models\Database\PrivilegeLevelDatabase;
use System\App\AppLogger;
use System\App\LDAPLogger;
use System\Traits\DomainTools;

class ADAuth
{

    /**
     * Load Domain Tools Trait
     *
     */
    use DomainTools;

    /**
     * @var ADConnection
     */
    private $connection;

    /**
     * @var LDAPLogger|null
     */
    private $logger;

    /**
     * ADAuth constructor.
     *
     */
    public function __construct()
    {
        $this->logger = LDAPLogger::get();
    }

    /**
     *
     * @param resource $connection
     * @param string $baseDN
     * @param string $groupName
     *
     * @return string
     */
    public static function getGroupDN($connection, string $baseDN, string $groupName)
    {
        $filter = "(&(objectClass=group)(cn=" . $groupName . "))";
        $result = ldap_search($connection, $baseDN, $filter);
        $info = ldap_get_entries($connection, $result);
//var_dump($info);
        if (is_array($info)) {
            if (key_exists("count", $info)) {
                if ($info["count"] == 1) {
                    if (key_exists("distinguishedname", $info[0])) {
                        return $info[0]["distinguishedname"][0];
                    }
                }
            }
        }
    }

    /**
     * Authenticates the user against AD and
     * returns a complete privileged user.
     *
     * @param $username
     * @param $password
     * @return User|false
     * @throws \System\App\AppException
     */
    public function authenticate($username, $password)
    {
        $username = strtolower($username);
        $domain = DomainDatabase::getAD_FQDN();

// Prepare connection username by appending domain name if not already provided
        $ldapUser = $username;
        if (!is_null($domain) and !strpos($username, $domain)) {
            $ldapUser = $username . "@" . $domain;
        }
// Connect to LDAP server
        $this->connection = ADConnection::get();
        if ($this->connection->verifyCredentials($ldapUser, $password)) {
            $user = new User($username);
            $webUser = $this->loadWebUser($user);

            if ($webUser->getPrivilegeLevels() !== null or $webUser->superAdmin) {
                $this->logger->info("User authentication passed");

                return $webUser;
            }
        }
        $this->logger->info("User authentication failed");

        return false;

    }

    /**
     * Loads all appropriate privile level ids into the
     * user
     *
     * @param User $webUser
     *
     * @return User
     * @throws \System\App\AppException
     */
    private function loadWebUser(User $webUser): User
    {
        //$adUser = ADUsers::getApplicationScopeUser($webUser->getUsername());
        $allPrivilegeLevels = PrivilegeLevelDatabase::get();
        /* @var $privilegeLevel PrivilegeLevel */
        foreach ($allPrivilegeLevels as $privilegeLevel) {
            //var_dump(ADUsers::isUserInGroup($webUser->username, $privilegeLevel->getAdGroup()));
            if (ADUsers::isUserInGroup($webUser->username, $privilegeLevel->getAdGroup())) {
                $webUser->addPrivilegeLevel($privilegeLevel);
                if ($privilegeLevel->getSuperAdmin()) {
                    $webUser->setSuperUser(true);
                    return $webUser;
                }
            }
        }

        // var_dump($webUser);
        return $webUser;
    }


}
