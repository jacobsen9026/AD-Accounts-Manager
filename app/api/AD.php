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
    private $server;
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

    function __construct($districtID, $server = null, $username = null, $password = null) {

        if (isset(self::$instance)) {
            return self::$instance;
        } else {
            $this->districtID = $districtID;
            self::$instance = $this;
            $this->logger = \system\app\AppLogger::get();
            $this->server = District::getAD_FQDN($this->districtID);
            if (!is_null($server)) {
                $this->server = $server;
            }
            $this->baseDN = '';
            $afterFirst = false;
            foreach (explode(".", $this->server) as $part) {
                if ($afterFirst) {
                    $this->baseDN .= ',';
                }
                $this->baseDN .= 'DC=' . $part;
                $afterFirst = true;
            }
            $this->testUserDN = "CN=" . $this->testUserName . ",CN=Users," . $this->baseDN;
            $this->username = District::getADUsername($this->districtID) . "@" . District::getAD_FQDN($this->districtID);
            if (!is_null($username)) {
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
    }

    /**
     *
     * @return AD
     */
    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function connect() {
        $this->connection = ldap_connect("ldap://" . $this->server)
                or die("Could not connect to LDAP server.");

        if ($this->connection) {
            try {
                $this->connected = ldap_bind($this->connection, $this->username, $this->password);
            } catch (Exception $ex) {
                $this->logger->warning($ex);
            }

            if ($this->connected) {
                $this->logger->info("LDAP bind successful to " . $this->server . " using credentials: " . $this->username . ' ' . $this->password);

                return $this;
            } else {
                $this->logger->warning("LDAP bind failed  to " . $this->server . " using credentials: " . $this->username . ' ' . $this->password);

                return false;
            }
        }
    }

    public function getConnectionResult() {


        if (!empty($this->username) and!empty($this->password)) {
            $this->connection = ldap_connect("ldap://" . $this->server)
                    or die("Could not connect to LDAP server.");

            if ($this->connection) {

                $ldapbind = ldap_bind($this->connection, $this->username, $this->password);

                if ($ldapbind) {
                    $this->logger->info("LDAP bind successful to " . $this->server . " using credentials: " . $this->username . ' ' . $this->password);

                    ldap_unbind($this->connection);
                    return true;

                    ldap_unbind($this->connection);
                } else {
                    $this->logger->warning("LDAP bind failed  to " . $this->server . " using credentials: " . $this->username . ' ' . $this->password);
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
        $info["cn"] = $this->testUserName;
        //var_dump($this->baseDN);
        try {
            $r = ldap_delete($this->connection, $this->testUserDN);
        } catch (Exception $ex) {
            $r = false;
        }

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
        $filter = '(CN=' . $this->testUserName . ')';
        $result = ldap_search($this->connection, "CN=users," . $this->baseDN, $filter);
        $info = ldap_get_entries($this->connection, $result);
        //var_dump($filter);
        //var_dump($result);
        //var_dump($info);
        if ($result != false) {
            if (key_exists("count", $info) and $info["count"] == 1) {
                return true;
            }
        }
        return false;
    }

}
