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

namespace app\auth;

/**
 * Description of LDAP
 *
 * @author cjacobsen
 */
use app\models\database\AuthDatabase;
use app\models\user\User;
use system\app\auth\AuthException;
use app\models\user\Privilege;
use app\api\AD;
use app\models\database\PrivilegeLevelDatabase;
use system\app\AppLogger;

class ADAuth {

    /**
     * Load Domain Tools Trait
     *
     */
    use \system\traits\DomainTools;

    private $connection;

//put your code here
    public function authenticate($username, $password) {

        $passed = false;
        $username = strtolower($username);
        $logger = \system\app\AppLogger::get();
        $server = AuthDatabase::getLDAPServer();
        $domain = AuthDatabase::getLDAP_FQDN();
// Prepare connection username by appending domain name if not already provided
        if (!is_null($domain) and!strpos($username, $domain)) {
            $ldapUser = $username . "@" . $domain;
        }
// Connect to LDAP server
        $this->connection = AD::connect($server, $ldapUser, $password);


        if (is_resource($this->connection)) {
            $userLevels = array();
            $adAPI = AD::get();
            echo "<br/><br/><br/><br/><br/><br/><br/><br/>";
            $allPrivilegeLevels = PrivilegeLevelDatabase::get();
            foreach ($allPrivilegeLevels as $privilegeLevel) {
                AppLogger::get()->info($privilegeLevel->getAdGroup());
                if ($adAPI->isUserInGroup($username, $privilegeLevel->getAdGroup())) {

                    $userLevels[] = $privilegeLevel;
                    $passed = true;
                }
            }
            AppLogger::get()->info($userLevels);
            AppLogger::get()->info($adAPI->isUserInGroup($username, 'SAM Tech'));
//return false;



            $connected = true;

// If bind was successful, user was found in LDAP continue processing

            $logger->info("LDAP bind successful to " . $server . " using credentials: " . $ldapUser);
// Explode ldap FQDN into a base DN
            $baseDN = self::FQDNtoDN($domain);

            $adGroupName = '';
            $superAdmin = false;
            if ($adAPI->isUserInGroup($username, AuthDatabase::getSuperUserADGroup())) {
                $passed = true;

                //$fullName = $superUserInfo[$i]["name"][0];
                // $fullName = "Finish me line 114 ADAuth";
                $privilege = Privilege::TECH;
                $superAdmin = true;
                $adGroupName = AuthDatabase::getSuperUserADGroup();
            }


            if ($passed) {

                //var_dump("here");
                $adUser = $adAPI->getUser($username);
                // var_dump($adUser);
                AppLogger::get()->info($username . " successfully logged in");
                if (!isset($privilege)) {
                    $privilege = Privilege::BASIC;
                }
                if (!$fullName = $adUser['displayname'][0]) {
                    $fullName = $username;
                }
                $user = new User();
                $user->setFullName($fullName)
                        ->setPrivilege($privilege)
                        ->setUsername($username)
                        ->setPrivilegeLevels($userLevels)
                        ->setSuperUser($superAdmin);
                AppLogger::get()->info($user);
                return $user;


                exit;
                return true;
            } else {
                throw new AuthException(AuthException::NOT_AUTHORIZED);
            }
        }
    }

    /**
     *
     * @param type $connection
     * @param type $baseDN
     * @param type $groupName
     * @return string
     */
    public static function getGroupDN($connection, $baseDN, $groupName) {
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

    public static function testConnection($server, $username, $password) {
        $logger = \system\app\AppLogger::get();
        $ldapconn = ldap_connect("ldap://" . $server)

                or die("Could not connect to LDAP server.");

        if ($ldapconn) {

// binding anonymously
            $ldapbind = ldap_bind($ldapconn, $username, $password);

            if ($ldapbind) {
                $logger->info("LDAP Auth bind successful to " . $server . " using credentials: " . $username . ' ' . $password);
                ldap_unbind($ldapconn);
                return true;
            } else {
                $logger->warning("LDAP Auth bind failed  to " . $server . " using credentials: " . $username . ' ' . $password);
                $error = ldap_error($ldapconn);
                ldap_close($ldapconn);
                return $error;
            }
        }
    }

}
