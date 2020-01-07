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

namespace app\api;

/**
 * Description of LDAP
 *
 * @author cjacobsen
 */
use app\models\district\District;
use app\database\Schema;

class AD {

//put your code here
// Create a configuration array.

    private $username;
    private $password;
    private $fqdn;
    private $baseDN;
    private $connection;
    private $connected;
    private $testUserDN;
    private $testUserName = "1891351591_SchoolAccountsManager_Test_User";
    private $districtID;

    /** @var AppLogger The application logger */
    private $logger;

    /** @var AD|null */
    public static $instance;

    function __construct($districtID, $fqdn = null, $username = null, $password = null) {

        if (isset(self::$instance)) {
            return self::$instance;
        } else {
            $this->initialize($districtID, $fqdn, $username, $password);
        }
    }

    private function initialize($districtID, $fqdn, $username, $password) {
        $this->districtID = $districtID;
        self::$instance = $this;
        $this->logger = \system\app\AppLogger::get();
        $this->fqdn = District::getAD_FQDN($this->districtID);
        if (!is_null($fqdn)) {
            $this->fqdn = $fqdn;
        }
        $baseDN = District::getAD_BaseDN($districtID);
        if ($baseDN == "") {
            $this->baseDN = District::parseBaseDNFromFQDN($this->fqdn);

            $this->testUserDN = "CN=" . $this->testUserName . ",CN=Users," . $this->baseDN;
        } else {
            $this->baseDN = $baseDN;
            $this->testUserDN = "CN=" . $this->testUserName . "," . $baseDN;
        }
        if ((is_null($username))) {
            if (strpos($username, "\\") > 0 or strpos($username, "@") > 0) {
                $this->username = District::getADUsername($this->districtID) .
                        "@" . District::getAD_FQDN($this->districtID);
            } else {
                $this->username = District::getADUsername($this->districtID);
            }
        } else {
            $this->username = $username;
        }

        $this->password = District::getADPassword($this->districtID);
        if (!is_null($password)) {
            $this->password = $password;
        }
        $this->logger->debug($this->username);
        $this->logger->debug($this->password);
        if (!empty($this->username) and!empty($this->password)) {
            $this->connect();
        }
    }

    /**
     *
     * @return AD
     */
    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self(1);
        }
        return self::$instance;
    }

    public function connect() {
        $this->connection = ldap_connect("ldap://" . $this->fqdn)
                or die("Could not connect to LDAP server.");

        if ($this->connection) {
            try {
                $this->connected = ldap_bind($this->connection, $this->username, $this->password);
            } catch (Exception $ex) {
                $this->logger->warning($ex);
            }

            if ($this->connected) {
                $this->logger->info("LDAP bind successful to " . $this->fqdn . " using credentials: " . $this->username . ' ' . $this->password);

                return $this;
            } else {
                $this->logger->warning("LDAP bind failed  to " . $this->fqdn . " using credentials: " . $this->username . ' ' . $this->password);

                return false;
            }
        }
    }

    public function query($filter = null, $base_dn = null) {
        if (is_null($filter)) {
            $filter = "(&(objectClass=person)(objectClass=user))";
        }
        if (is_null($base_dn)) {
            $base_dn = $this->baseDN;
        }
        $result = ldap_search($this->connection, $base_dn, $filter);
        if ($result != false) {
            $info = ldap_get_entries($this->connection, $result);
            $this->logger->info($info);
            return $info;
        }
        return false;
    }

    public function getConnectionResult() {


        if (!empty($this->username) and!empty($this->password)) {
            $this->connection = ldap_connect("ldap://" . $this->fqdn)
                    or die("Could not connect to LDAP server.");

            if ($this->connection) {
                if ($this->connected) {
                    return true;
                }

                $ldapbind = ldap_bind($this->connection, $this->username, $this->password);

                if ($ldapbind) {
                    $this->logger->info("LDAP bind successful to " . $this->fqdn . " using credentials: " . $this->username . ' ' . $this->password);

                    ldap_unbind($this->connection);
                    return true;

                    ldap_unbind($this->connection);
                } else {
                    $this->logger->warning("LDAP bind failed  to " . $this->fqdn . " using credentials: " . $this->username . ' ' . $this->password);
                    $error = ldap_error($this->connection);
                    ldap_close($this->connection);
                    return $error;
                }
            }
            return "Unable to connect to LDAP server";
        }
        return "No Username or Password";
    }

    public function deleteTestUser() {

        //var_dump("Deleting test user");
        try {
            $r = ldap_delete($this->connection, $this->testUserDN);
        } catch (Exception $ex) {
            $r = false;
        }
        //var_dump($r);
        $error = ldap_error($this->connection);
        if (!$r) {
            return $error;
        } else {

            return true;
        }
    }

    public function createTestUser() {
        if ($this->testUserExists()) {
            $this->deleteTestUser();
        }
        $info["cn"] = $this->testUserName;
        $info["objectclass"] = "User";
//var_dump($this->baseDN);
        try {
            $r = ldap_add($this->connection, $this->testUserDN, $info);
        } catch (Exception $ex) {
            $r = false;
        }

        $error = ldap_error($this->connection);
//var_dump($error);
        if (!$r) {
            return $error;
        } else {
            if ($this->testUserExists()) {
                $this->deleteTestUser();
            }
            return true;
        }
    }

    private function testUserExists() {
        $filter = '(objectclass=user)';
        $result = $this->query($filter, $this->testUserDN);

        if ($result != false) {
            return true;
        }
        return false;
    }

    private function processUser($user) {
        $aduser = new \app\models\district\ADUser();
        if (key_exists("givenname", $user)) {
            $aduser->setFirstName($user["givenname"][0]);
        }
        if (key_exists("sn", $user)) {
            $aduser->setLastName($user["sn"][0]);
        }
        if (key_exists("middlename", $user)) {
            $aduser->setMiddleName($user["middlename"][0]);
        }
        if (key_exists("cn", $user)) {
            $aduser->setFullName($user["cn"][0]);
        }
        if (key_exists("employeeid", $user)) {
            $aduser->setId($user["employeeid"][0]);
        }
        if (key_exists("homedirectory", $user)) {
            $aduser->setHdir($user["homedirectory"][0]);
        }
        if (key_exists("homedrive", $user)) {
            $aduser->setHdrv($user["homedrive"][0]);
        }
        if (key_exists("scriptpath", $user)) {
            $aduser->setScript($user["scriptpath"][0]);
        }
        if (key_exists("streetaddress", $user)) {
            $aduser->setStreet($user["streetaddress"][0]);
        }
        if (key_exists("description", $user)) {
            $aduser->setDescription($user["description"][0]);
        }
        if (key_exists("memberof", $user)) {
            foreach ($user["memberof"] as $key => $memberGroup) {
                if (strval($key) != "count") {
                    $groups[] = $memberGroup;
                }
            }

            $aduser->setGroups($groups);
        }

        if (key_exists("lockouttime", $user)) {
            if (intval($user["lockouttime"][0]) > 0) {
                $aduser->setLockedOut(true);
            } else {
                $aduser->setLockedOut(false);
            }
        } else {
            $aduser->setLockedOut(false);
        }

        return $aduser;
    }

    public function getUser($username) {
        $filter = '(&(objectClass=person)(objectClass=user)(sAMAccountName=' . $username . '))';
        return $this->queryObject($filter);
    }

    public function getStudentUser($username) {

        $studentGroupDN = $this->getGroupDN("Students");
        $filter = '(&(objectClass=person)(memberOf:1.2.840.113556.1.4.1941:=' . $studentGroupDN . ')(objectClass=user)(sAMAccountName=' . $username . '))';
        return $this->queryObject($filter);
    }

    public function getStaffUser($username) {

        $studentGroupDN = $this->getGroupDN("Students");
        $filter = '(&(objectClass=person)(!(memberOf:1.2.840.113556.1.4.1941:=' . $studentGroupDN . '))(objectClass=user)(sAMAccountName=' . $username . '))';
        return $this->queryObject($filter);
    }

    public function unlockUser($username) {
        $entry = array("lockouttime" => 0);
        //var_dump($this->getUserDN($username));
        ldap_mod_replace($this->connection, $this->getUserDN($username), $entry);
        $filter = '(&(objectClass=person)(objectClass=user)(sAMAccountName=' . $username . '))';
        return $this->queryObject($filter);
    }

    public function listStudentUsers($usernameFragment) {
        $studentGroupDN = $this->getGroupDN("Students");
        //var_dump($studentGroupDN);
        $filter = '(&(objectClass=person)(objectClass=user)'
                . '(memberOf:1.2.840.113556.1.4.1941:=' . $studentGroupDN . ')'
                . '(|(sAMAccountName=*' . $usernameFragment . '*)(givenname=' . $usernameFragment . '*)(sn=' . $usernameFragment . '*)))';
        return $this->listUsers($filter);
    }

    private function listUsers($filter) {
        $result = $this->query($filter);
        //var_dump($filter);
        if ($result != false) {
            if (key_exists("count", $result)) {
                foreach ($result as $user) {
                    //var_dump($user);
                    if (key_exists("samaccountname", $user))
                        $usernames[] = $user["samaccountname"][0];
                }
            }
        }
        if (isset($usernames)) {

//var_dump($usernames);
            sort($usernames);
            return $usernames;
        }
        return false;
    }

    public function listStaffUsers($usernameFragment) {
        $studentGroupDN = $this->getGroupDN("Students");

        // var_dump($studentGroupDN);
        $filter = '(&(objectClass=person)(objectClass=user)'
                . '(!(objectClass=computer))'
                . '(!(memberOf:1.2.840.113556.1.4.1941:=' . $studentGroupDN . '))'
                . '(|(sAMAccountName=' . $usernameFragment . '*)(givenname=' . $usernameFragment . '*)(sn=' . $usernameFragment . '*)))';

        return $this->listUsers($filter);
    }

    private function queryObject($filter) {
        $result = $this->query($filter);

        if ($result != false) {
            if (key_exists("count", $result) and $result["count"] == 1) {
                $user = $result[0];
                //var_dump($user);
                return $this->processUser($user);
            }
        }
        return false;
    }

    /**
     *
     * @param type $connection
     * @param type $baseDN
     * @param type $groupName
     * @return string
     */
    public function getGroupDN($groupName) {
        $filter = "(&(objectClass=group)(cn=" . $groupName . "))";
        //var_dump($filter);
        return $this->getObjectDN($filter);
    }

    /**
     *
     * @param type $connection
     * @param type $baseDN
     * @param type $user
     * @return string
     */
    public function getUserDN($user) {
        $filter = "(&(objectClass=person)(samaccountname=" . $user . "))";
        return $this->getObjectDN($filter);
    }

    private function getObjectDN($filter) {
        //var_dump($filter);
        //var_dump($this->baseDN);
        $result = ldap_search($this->connection, $this->baseDN, $filter);
        $info = ldap_get_entries($this->connection, $result);
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

}
