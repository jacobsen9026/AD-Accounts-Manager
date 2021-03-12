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

namespace App\Api;

/**
 * Description of LDAP
 *
 * @author cjacobsen
 */

use App\Models\Database\DomainDatabase;
use App\Models\Domain\Domain;
use Exception;
use System\App\LDAPLogger;
use App\Models\User\PermissionLevel;
use App\Models\User\PermissionHandler;
use System\App\AppException;
use System\Traits\DomainTools;

class AD
{

    use DomainTools;


    /** @var AD|null */
    public static $instance;
    private $fqdn;
    private $baseDN;
    private $connection;
    private $testUserDN;
    private $testUserName = "1891351591_SchoolAccountsManager_Test_User";
    /**
     *
     * @var string Text version of the connection state.
     */
    private $connectionStatus;
    /**
     *
     * @var Domain
     */
    private $domain;
    /** @var LDAPLogger The application logger */
    private $logger;

    function __construct($domainID, $fqdn = null, $username = null, $password = null)
    {

        if (isset(self::$instance)) {
            return;
        } else {
            $this->initialize($domainID, $fqdn, $username, $password);
        }
    }

    private function initialize($domainID, $fqdn, $username, $password)
    {


        $domainID1 = $domainID;

        $this->domain = DomainDatabase::getDomain();
        self::$instance = $this;
        $this->logger = LDAPLogger::get();
        $this->fqdn = DomainDatabase::getAD_FQDN($domainID1);
        if (!is_null($fqdn)) {
            $this->fqdn = $fqdn;
        }
        if ($this->fqdn != '') {

            $baseDN = DomainDatabase::getAD_BaseDN($domainID);

            $this->baseDN = $baseDN;
            $this->testUserDN = "CN=" . $this->testUserName . "," . $baseDN;

            if ((is_null($username))) {
                $username = DomainDatabase::getADUsername($domainID1);
                if (strpos($username, "\\") === false and strpos($username, "@") === false) {
                    $username = $username .
                        "@" . DomainDatabase::getAD_FQDN($domainID1);
                }
            }
            $username1 = $username;


            $password1 = DomainDatabase::getADPassword($domainID1);
            if (!is_null($password)) {
                $password1 = $password;
            }
            if (!empty($username1) and !empty($password1)) {
//var_dump($this->connection);
                $this->connection = $this->connect($this->fqdn, $username1, $password1);
                if (!is_resource($this->connection) or !get_resource_type($this->connection) == 'ldap link') {
                    $this->connectionStatus = $this->connection;
//var_dump($this->connection);
                }
            }
        }
    }

    /**
     *
     * @param string $fqdn
     * @param string $username
     * @param string $password
     *
     * @return resource|string Returns the connection resource or the error message
     */
    public static function connect($fqdn, $username, $password)
    {
        $connectionStatus = false;
        $logger = LDAPLogger::get();
        $hostname = "ldap://" . $fqdn;
        $logger->debug($hostname);
        $connection = ldap_connect($hostname);
        $logger->info(ldap_error($connection));
        if ($connection) {
            $logger->debug("LDAP URL " . $fqdn . " reachable");
            try {
                $connection = AD::setLDAPOptions($connection);
                if (DomainDatabase::getAD_UseTLS()) {
                    if (!ldap_start_tls($connection)) {
                        $logger->error(ldap_error($connection));
                        $connectionStatus = "SSL ERROR: COULD NOT BIND TO TLS";
                        $logger->error("ad SSL Error");

                        throw new AppException($connectionStatus);
                    }
                }
                if (ldap_bind($connection, $username, $password)) {
                    $connectionStatus = true;
                    $logger->info("LDAP bind successful to " . $fqdn . " using credentials: " . $username);
                    return $connection;
                } else {
                    $logger->error(ldap_error($connection));
                    $connectionStatus = "INVALID CREDENTIALS/ACCOUNT LOCKED";
                    $logger->warning("LDAP bind failed  to " . $fqdn . " using credentials: " . $username . ' ' . $password);

                    throw new AppException($connectionStatus);
                }


            } catch
            (Exception $ex) {
                $logger->warning($ex);
            }
        } else {
            $connectionStatus = "LDAP SERVER UNREACHABLE";
            $logger->warning("LDAP Server is unreachable");
            throw new AppException($connectionStatus);
        }

        if ($connectionStatus === false) {
            throw new AppException('General Unable to connect to Active Directory!');
        }
        return $connectionStatus;
    }

    private
    static function setLDAPOptions($connection)
    {
        ldap_set_option(null, LDAP_OPT_DEBUG_LEVEL, 7);
        ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($connection, LDAP_OPT_X_TLS_CERTFILE, CONFIGPATH . DIRECTORY_SEPARATOR . "activedirectory" . DIRECTORY_SEPARATOR . "ad.pem");
        return $connection;
//ldap_set_option($this->connection, LDAP_OPT_X_TLS_REQUIRE_CERT, LDAP_OPT_X_TLS_NEVER);
    }

    /**
     *
     * @return AD
     */
    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = new self(1);
        }
        return self::$instance;
    }


    public function createTestUser()
    {
        if ($this->testUserExists()) {
            $this->deleteTestUser();
        }
        $info["cn"] = $this->testUserName;
        $info["objectclass"] = "user";
        try {
            $r = ldap_add($this->connection, $this->testUserDN, $info);
        } catch (Exception $ex) {
            $r = false;
        }

        $error = ldap_error($this->connection);
        //$errno = ldap_errno($this->connection);


        if (!$r) {
            return $error;
        } else {
            if ($this->testUserExists()) {
                $this->deleteTestUser();
            }
            return "true";
        }
    }

    private function testUserExists()
    {
        $filter = '(objectclass=user)';
        $result = $this->query($filter, $this->testUserDN);

        if ($result != false) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param string $filter
     * @param string $base_dn
     *
     * @return array|false
     */
    public
    function query(string $filter, string $base_dn = null)
    {
        if (is_null($base_dn)) {
            $base_dn = $this->baseDN;
        }
        $this->logger->info($base_dn);
        $this->logger->info($filter);
        $result = ldap_search($this->connection, $base_dn, $filter);

        $this->logger->info($result);
        if ($result != false) {
            $info = ldap_get_entries($this->connection, $result);

            $this->logger->info($info);
            return $info;
        }
        return false;
    }

    public function deleteTestUser()
    {

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

    /**
     * Returns an array of usernames that match
     * the search term for username, first name,
     * or last name.
     *
     * @param string $searchTerm
     *
     * @return type
     */
    public function listDomainGroups($searchTerm)
    {
//var_dump($searchTerm);


        $filter = '(&(objectClass=group)'
            . '(|(cn=*' . $searchTerm . '*)(sAMAccountName=*' . $searchTerm . '*)(description=*' . $searchTerm . '*)(mail=*' . $searchTerm . '*)))';
//var_dump($filter);
        return $this->listGroups($filter);
    }

    private function listGroups($filter)
    {
        $result = $this->query($filter);
        if ($result != false) {
            if (key_exists("count", $result)) {
                foreach ($result as $group) {
                    if (is_array($group)) {
                        if (key_exists("cn", $group)) {
                            $insert["label"] = $group["cn"][0];
                            $insert["value"] = $group["distinguishedname"][0];
                            if (self::hasPermission($group, PermissionLevel::GROUPS, PermissionLevel::GROUP_READ)) {
                                $groups[] = $group["cn"][0];
                            }
                        }
                    }
                }
            }
        }
        if (isset($groups)) {

//var_dump($usernames);
            sort($groups);
            return $groups;
        }
        return false;
    }

    private static function hasPermission($target, $permissionType, $requiredLevel)
    {
        //var_dump($ldapResponse);
        if (is_array($target)) {
            $ou = substr($target['distinguishedname'][0], strpos($target['distinguishedname'][0], ',') + 1);
        } else {
            $ou = $target;
        }
        return PermissionHandler::hasPermission($ou, $permissionType, $requiredLevel);
        //var_dump($ou);
        //var_dump($ou);
    }

    /**
     * Returns an array of usernames that match
     * the search term for username, first name,
     * or last name.
     *
     * @param string $searchTerm
     *
     * @return type
     */
    public function listDomainUsers($searchTerm)
    {
//var_dump($searchTerm);


        $filter = '(&(objectClass=user)'
            . '(|(cn=*' . $searchTerm . '*)(sAMAccountName=*' . $searchTerm . '*)(description=*' . $searchTerm . '*)(mail=*' . $searchTerm . '*)))';
//var_dump($filter);
        return $this->listUsers($filter);
    }

    /**
     * Returns an array of usernames that match the input filter
     * Returns false if no users were found.
     *
     * @param string $filter
     *
     * @return array|false
     */
    private function listUsers($filter)
    {
        //var_dump($filter);
        $result = $this->query($filter);
        //var_dump($result);
        if ($result != false) {
            if (key_exists("count", $result)) {
                foreach ($result as $user) {
                    if (!is_int($user)) {
                        if (key_exists("samaccountname", $user) and key_exists('distinguishedname', $user)) {


                            if (self::hasPermission($user, PermissionLevel::USERS, PermissionLevel::USER_READ)) {
                                $usernames[] = $user["samaccountname"][0];
                            }
                        }
                    }
                }
            }
        }
        if (isset($usernames)) {

//var_dump($usernames);
            sort($usernames);
            return $usernames;
        }
        return [];
    }


    /**
     * user requires Group Add permissions to list OU's
     * Returns an array of OU's that match the input filter
     * Returns false if no OU's were found.
     *
     * @param string $filter
     *
     * @return array|false
     */
    public function listOUs($searchTerm)
    {
        $this->logger->info($searchTerm);
        $filter = '(&(objectClass=organizationalUnit)(|(name=*' . $searchTerm . '*)(ou=*' . $searchTerm . '*)))';

        $result = $this->query($filter);
        if ($result != false && key_exists("count", $result)) {

            foreach ($result as $ou) {
                if (!is_int($ou) && key_exists('distinguishedname', $ou)) {

                    if (self::hasPermission($ou, PermissionLevel::GROUPS, PermissionLevel::GROUP_ADD)) {
                        $ous[] = $ou["distinguishedname"][0];
                    }

                }
            }

        }
        if (isset($ous)) {
            sort($ous);
            return $ous;
        }
        return [];
    }

    public function getAllSubOUs($dn, $array = null)
    {
        $ous = [];
        if ($this->ouExists($dn) or $dn == DomainDatabase::getAD_BaseDN(1)) {
            //var_dump('Searching sub ous of ' . $dn);
            $filter = '(objectClass=organizationalUnit)';
            $result = $this->list($filter, $dn);
            //var_dump($result);
            foreach ($result as $resultEntry) {
                //var_dump($resultEntry);
                if (is_array($resultEntry)) {
                    $ou = $resultEntry["distinguishedname"][0];
                    if ($resultEntry != null and $ou != null and $ou != '' and $ou != $dn) {
                        if ($this->hasSubOUs($ou)) {
                            $ous [$ou] = $this->getAllSubOUs($ou, $array);
                        } else {
                            $ous[] = $ou;
                        }
                    }
                }
            }
            return $ous;
        }
        return false;
    }

    public function ouExists($dn)
    {
        //$this->logger->debug("Testing if " . $dn . " exists");
//$test = $this->query('(distinguishedName="' . $dn . '")');
        $test = $this->read('(&(objectClass=organizationalUnit))', $dn);
//var_dump($test);
        if ($test === false or $test["count"] == 0) {
            $this->logger->warning($dn . " is an invalid OU");
            return false;
        }
        //$this->logger->debug($dn . " is a valid OU");
        return true;
    }

    public
    function read($filter = null, $base_dn = null)
    {
        if (is_null($filter)) {
            $filter = "(&(objectClass=person)(objectClass=user))";
        }
        if (is_null($base_dn)) {
            $base_dn = $this->baseDN;
        }
        //$this->logger->info($base_dn);
        //$this->logger->info($filter);
        //var_dump($base_dn);
        //var_dump($filter);
        $result = ldap_read($this->connection, $base_dn, $filter, ["*"], 0, 50, 10);
        if ($result != false) {
            $info = ldap_get_entries($this->connection, $result);
            //$this->logger->info($info);

            return $info;
        }
        return false;
    }

    public
    function list($filter = null, $base_dn = null)
    {
        if (is_null($filter)) {
            $filter = "(&(objectClass=person)(objectClass=user))";
        }
        if (is_null($base_dn)) {
            $base_dn = $this->baseDN;
        }
        $result = ldap_list($this->connection, $base_dn, $filter, ["*"], 0, 50, 10);
        //var_dump($result);
        if ($result != false) {
            $info = ldap_get_entries($this->connection, $result);
            return $info;
        }
        return false;
    }

    public function hasSubOUs($dn)
    {
        if ($this->ouExists($dn)) {
            $filter = '(objectClass=organizationalUnit)';
            $result = $this->list($filter, $dn);
            if ($result["count"] > 0) {
                return true;
            }
        }
        return false;
    }


}
