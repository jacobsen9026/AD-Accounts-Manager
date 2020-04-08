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
use app\models\Auth;
use app\models\user\User;
use system\app\auth\AuthException;
use app\models\user\Privilege;

abstract class LDAP {

//put your code here
    public static function authenticate($username, $password) {
        $username= strtolower($username);
        $logger = \system\app\AppLogger::get();
        $server = Auth::getLDAPServer();
        $domain = Auth::getLDAP_FQDN();
        // Prepare connection username by appending domain name if not already provided
        if (!is_null($domain) and!strpos($username, $domain)) {
            $ldapUser = $username . "@" . $domain;
        }
        // Connect to LDAP server
        $connection = ldap_connect("ldap://" . $server)
                or die("Could not connect to LDAP server.");

        if ($connection) {
            try {
                // Set options and bind
                ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);
                $connected = ldap_bind($connection, $ldapUser, $password);
            } catch (Exception $ex) {
                $logger->warning($ex);
            }
            // If bind was successful, user was found in LDAP continue processing
            if ($connected) {
                $logger->info("LDAP bind successful to " . $server . " using credentials: " . $ldapUser);
                // Explode ldap FQDN into a base DN
                $baseDN = '';
                $afterFirst = false;
                foreach (explode(".", $domain) as $part) {
                    if ($afterFirst) {
                        $baseDN .= ',';
                    }
                    $baseDN .= 'DC=' . $part;
                    $afterFirst = true;
                }
                $techDN = self::getGroupDN($connection, $baseDN, Auth::getTechADGroup());
                $adminDN = self::getGroupDN($connection, $baseDN, Auth::getAdminADGroup());
                $powerDN = self::getGroupDN($connection, $baseDN, Auth::getPowerADGroup());
                $basicDN = self::getGroupDN($connection, $baseDN, Auth::getBasicADGroup());
                //var_dump(Auth::getBasicADGroup());
                //var_dump($basicDN);
                //var_dump($username);
                $filter = "(sAMAccountName=$username)";
                $result = ldap_search($connection, $baseDN, $filter);
                $info = ldap_get_entries($connection, $result);
                $filterBasic = "(&(sAMAccountName=" . $username . ")(memberOf:1.2.840.113556.1.4.1941:=" . $basicDN . "))";
                $filterPower = "(&(sAMAccountName=" . $username . ")(memberOf:1.2.840.113556.1.4.1941:=" . $powerDN . "))";
                $filterAdmin = "(&(sAMAccountName=" . $username . ")(memberOf:1.2.840.113556.1.4.1941:=" . $adminDN . "))";
                $filterTech = "(&(sAMAccountName=" . $username . ")(memberOf:1.2.840.113556.1.4.1941:=" . $techDN . "))";
                //var_dump($filterBasic);
                $resultBasic = ldap_search($connection, $baseDN, $filterBasic);
                $resultPower = ldap_search($connection, $baseDN, $filterPower);
                $resultAdmin = ldap_search($connection, $baseDN, $filterAdmin);
                $resultTech = ldap_search($connection, $baseDN, $filterTech);
                //var_dump($resultBasic);
                $infoBasic = ldap_get_entries($connection, $resultBasic);
                $infoPower = ldap_get_entries($connection, $resultPower);
                $infoAdmin = ldap_get_entries($connection, $resultAdmin);
                $infoTech = ldap_get_entries($connection, $resultTech);
                $passed = false;
                //var_dump($infoBasic);
                //var_dump($infoPower);
                //var_dump($infoAdmin);
                //var_dump($infoTech);
                //exit;
                for ($i = 0; $i < $infoBasic["count"]; $i++) {
                    if ($username == strtolower($infoBasic[$i]["samaccountname"][0])) {
                        $passed = true;
                        $fullName = $infoBasic[$i]["name"][0];
                        $privilege = Privilege::BASIC;
                    }
                }
                for ($i = 0; $i < $infoPower["count"]; $i++) {
                    if ($username == strtolower($infoPower[$i]["samaccountname"][0])) {
                        $passed = true;

                        $fullName = $infoPower[$i]["name"][0];
                        $privilege = Privilege::POWER;
                    }
                }
                for ($i = 0; $i < $infoAdmin["count"]; $i++) {
                    if ($username == strtolower($infoAdmin[$i]["samaccountname"][0])) {
                        $passed = true;

                        $fullName = $infoAdmin[$i]["name"][0];
                        $privilege = Privilege::ADMIN;
                    }
                }
                for ($i = 0; $i < $infoTech["count"]; $i++) {
                    if ($username == strtolower($infoTech[$i]["samaccountname"][0])) {
                        $passed = true;

                        $fullName = $infoTech[$i]["name"][0];
                        $privilege = Privilege::TECH;
                    }
                }

                if ($passed) {
                    $user = new User();
                    $user->setFullName($fullName)
                            ->setPrivilege($privilege)
                            ->setUsername($username);
                    return $user;

                    var_dump($user);
                    exit;
                    return true;
                } else {
                    throw new AuthException(AuthException::NOT_AUTHORIZED);
                }
            } else {
                $error = ldap_error($connection);
                if ($error == "Invalid credentials") {

                    throw new AuthException(AuthException::BAD_PASSWORD);
                }
                throw new AuthException(AuthException::BAD_USER);
            }
        } else {

            var_dump(ldap_error($connection));
            $logger->warning("LDAP bind failed  to " . $server . " using credentials: " . $username . ' ' . $password);
            exit;
            return false;
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
