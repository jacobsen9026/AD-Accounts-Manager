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

use App\Models\Database\DistrictDatabase;
use App\Models\District\District;
use System\App\LDAPLogger;
use App\Models\User\PermissionLevel;
use App\Models\User\PermissionHandler;
use System\App\AppException;

class AD
{

    use \System\Traits\DomainTools;

//put your code here
// Create a configuration array.

    private $username;
    private $password;
    private $fqdn;
    private $baseDN;
    private $connection;
    private $testUserDN;
    private $testUserName = "1891351591_SchoolAccountsManager_Test_User";
    private $districtID;

    /**
     *
     * @var string Text version of the connection state.
     */
    private $connectionStatus;

    /**
     *
     * @var District
     */
    private $district;

    /** @var LDAPLogger The application logger */
    private $logger;

    /** @var AD|null */
    public static $instance;

    function __construct($districtID, $fqdn = null, $username = null, $password = null)
    {

        if (isset(self::$instance)) {
            return self::$instance;
        } else {
            $this->initialize($districtID, $fqdn, $username, $password);
        }
    }

    private function initialize($districtID, $fqdn, $username, $password)
    {


        $this->districtID = $districtID;

        $this->district = DistrictDatabase::getDistrict();
        self::$instance = $this;
        $this->logger = LDAPLogger::get();
        $this->fqdn = DistrictDatabase::getAD_FQDN($this->districtID);
        if (!is_null($fqdn)) {
            $this->fqdn = $fqdn;
        }
        if ($this->fqdn != '') {

            $baseDN = DistrictDatabase::getAD_BaseDN($districtID);

            $this->baseDN = $baseDN;
            $this->testUserDN = "CN=" . $this->testUserName . "," . $baseDN;

            if ((is_null($username))) {
                $username = DistrictDatabase::getADUsername($this->districtID);
                if (strpos($username, "\\") === false and strpos($username, "@") === false) {
                    $username = $username .
                        "@" . DistrictDatabase::getAD_FQDN($this->districtID);
                }
            }
            $this->username = $username;


            $this->password = DistrictDatabase::getADPassword($this->districtID);
            if (!is_null($password)) {
                $this->password = $password;
            }
            if (!empty($this->username) and !empty($this->password)) {
//var_dump($this->connection);
                $this->connection = $this->connect($this->fqdn, $this->username, $this->password);
                if (!is_resource($this->connection) or !get_resource_type($this->connection) == 'ldap link') {
                    $this->connectionStatus = $this->connection;
//var_dump($this->connection);
                }
            }
        }
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

    public function ouExists($dn)
    {
        //$this->logger->debug("Testing if " . $dn . " exists");
//$test = $this->query('(distinguishedName="' . $dn . '")');
        $test = $this->read('(&(objectClass=organizationalUnit))', $dn);
//var_dump($test);
        if ($test == "false" or $test["count"] == 0) {
            $this->logger->warning($dn . " is an invalid OU");
            return false;
        }
        $this->logger->debug($dn . " is a valid OU");
        return true;
    }


    /**
     *
     * @param type $fqdn
     * @param type $username
     * @param type $password
     *
     * @return resource|string Returns the connection resource or the error message
     */
    public static function connect($fqdn, $username, $password)
    {

        $logger = LDAPLogger::get();
        $connection = ldap_connect("ldap://" . $fqdn);
        $logger->info(ldap_error($connection));
        if ($connection) {
            $logger->debug("LDAP URL " . $fqdn . " reachable");
            try {
                AD::setLDAPOptions($connection);
                if (DistrictDatabase::getUseSSL()) {
                    if (ldap_start_tls($connection)) {
                        if (ldap_bind($connection, $username, $password)) {
                            $connectionStatus = true;
                            $logger->info("LDAP bind successful to " . $fqdn . " using credentials: " . $username);
                            return $connection;
                        } else {
                            $logger->error(ldap_error($connection));
                            $connectionStatus = "INVALID CREDENTIALS/ACCOUNT LOCKED";
                            $logger->warning("LDAP bind failed  to " . $fqdn . " using credentials: " . $username . ' ' . $password);
                        }
                    } else {
                        $logger->error(ldap_error($connection));
                        $connectionStatus = "SSL ERROR: COULD NOT BIND TO TLS";
                        $logger->error("ad SSL Error");
                    }
                }
            } catch (Exception $ex) {
                $logger->warning($ex);
            }
        } else {
            $connectionStatus = "LDAP SERVER UNREACHABLE";
            $logger->warning("LDAP Server is unreachable");
        }
        return $connectionStatus;
    }

    /**
     *
     * @param string $filter
     * @param type $base_dn
     *
     * @return boolean
     */
    public function query($filter, $base_dn = null)
    {
        /*
          if (is_null($filter)) {
          $filter = "(&(objectClass=person)(objectClass=user))";
          }
         *
         */
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

    private static function setLDAPOptions($connection)
    {
        ldap_set_option(null, LDAP_OPT_DEBUG_LEVEL, 7);
        ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($connection, LDAP_OPT_X_TLS_CERTFILE, CONFIGPATH . DIRECTORY_SEPARATOR . "activedirectory" . DIRECTORY_SEPARATOR . "ad.pem");
//ldap_set_option($this->connection, LDAP_OPT_X_TLS_REQUIRE_CERT, LDAP_OPT_X_TLS_NEVER);
    }


    public function read($filter = null, $base_dn = null)
    {
        if (is_null($filter)) {
            $filter = "(&(objectClass=person)(objectClass=user))";
        }
        if (is_null($base_dn)) {
            $base_dn = $this->baseDN;
        }
        //$this->logger->info($base_dn);
        //$this->logger->info($filter);

        $result = ldap_read($this->connection, $base_dn, $filter, ["*"], 0, 50, 10);
        if ($result != false) {
            $info = ldap_get_entries($this->connection, $result);
            //$this->logger->info($info);

            return $info;
        }
        return false;
    }

    public function list($filter = null, $base_dn = null)
    {
        if (is_null($filter)) {
            $filter = "(&(objectClass=person)(objectClass=user))";
        }
        if (is_null($base_dn)) {
            $base_dn = $this->baseDN;
        }
        // var_dump($filter);
        // var_dump($base_dn);
        $this->logger->info($base_dn);
        $this->logger->info($filter);
        $result = ldap_list($this->connection, $base_dn, $filter, ["*"], 0, 50, 10);

        if ($result != false) {


            //echo "wtf";
            //var_dump($this->connection);
            //var_dump($result);
            //var_dump(ldap_get_entries($this->connection, $result));
            //return;
            $info = ldap_get_entries($this->connection, $result);
            //var_dump(ldap_error($this->connection));
            //var_dump(ldap_get_entries($this->connection, $result));
            //return;
            //echo "wtf";
            //var_dump($info);
            //  return;
            // $this->logger->info($info);
            return $info;
        }
        return false;
    }

    public function getConnectionResult()
    {
        if (is_resource($this->connection) and get_resource_type($this->connection) == 'ldap link') {
            return true;
        }
        return $this->connectionStatus;
    }

    public function addUserToGroup($groupDN, $userDN)
    {
        if (PermissionHandler::hasPermission($this->getOUFromDN($groupDN), PermissionLevel::GROUPS, PermissionLevel::GROUP_CHANGE)) {
            $this->logger->debug(ldap_mod_add($this->connection, $groupDN, ['member' => $userDN]));
            $this->logger->debug(ldap_errno($this->connection));
            if (!ldap_errno($this->connection)) {
                return true;
            }
            throw new AppException(ldap_error($this->connection));
        } else {
            throw new AppException("You do not have permission to modify this group.", AppException::FAIL_GROUP_CHANGE_PERM);
        }
        return false;
    }

    public function removeUserFromGroup($groupDN, $userDN)
    {
        $this->logger->debug(ldap_mod_del($this->connection, $groupDN, ['member' => $userDN]));
        $this->logger->debug(ldap_errno($this->connection));
        if (!ldap_errno($this->connection)) {
            return true;
        }

        return false;
    }

    public function createGroup($name, $dn, $email)
    {
        $addgroup_ad['cn'] = "$name";
        $addgroup_ad['objectClass'][0] = "top";
        $addgroup_ad['objectClass'][1] = "group";
        $addgroup_ad['groupType'] = "2";

        //$addgroup_ad['member'] = $members;
        $addgroup_ad["sAMAccountName"] = $name;
        $this->logger->info($addgroup_ad);
        $this->logger->info($dn);
        ldap_add($this->connection, $dn, $addgroup_ad);
        if (!ldap_errno($this->connection)) {
            return true;
        } else {
            /*
             * @todo check for proper error
             */
            throw new AppException("That group already exists", AppException::GROUP_ADD_EXISTS);
        }
        $this->logger->warning(ldap_error($this->connection));
        return false;
    }

    public function deleteGroup($dn)
    {
        $ou = $this->getOUFromDN($dn);
        if ($this->hasPermission($ou, PermissionLevel::GROUPS, PermissionLevel::GROUP_DELETE)) {

            $delgroup_ad["distinguishedName"] = $dn;
            //$this->logger->info($addgroup_ad);
            $this->logger->info($dn);


            ldap_delete($this->connection, $dn);
            if (!ldap_errno($this->connection)) {
                return true;
            }
            $this->logger->warning(ldap_error($this->connection));
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

    public function createTestUser()
    {
        if ($this->testUserExists()) {
            $this->deleteTestUser();
        }
        $info["cn"] = $this->testUserName;
        $info["objectclass"] = "user";
//var_dump($this->baseDN);
        try {
            $r = ldap_add($this->connection, $this->testUserDN, $info);
//var_dump($r);
        } catch (Exception $ex) {
            $r = false;
        }

        $error = ldap_error($this->connection);
        $errno = ldap_errno($this->connection);


//var_dump($errno);
//var_dump($error);
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

    public function getUser($username, $baseDN = null)
    {
        $filter = '(&(objectClass=user)(sAMAccountName=' . $username . '))';

        $user = $this->queryObject($filter, $baseDN);
        $enabledFilter = '(&(userAccountControl:1.2.840.113556.1.4.803:=2)(sAMAccountName=' . $username . '))';
        $enabled = ['enabled' => false];
//var_dump($enabledResult);
        if ($this->queryObject($enabledFilter) == false) {
            $enabled = ['enabled' => true];
        }
        $this->logger->debug($user);
        $user = array_merge($user, $enabled);
        return $user;
    }

    public function getDomainUser($username)
    {
        return $this->getUser($username, self::FQDNtoDN(DistrictDatabase::getAD_FQDN(1)));
    }

    /**
     * Same as getUser but applies App permissions
     *
     * @param type $username
     *
     * @return array Raw LDAP response of user
     * @throws AppException If the user does not have permission to view
     */
    public function searchUser($username)
    {
        $user = self::getUser($username);
        if (self::hasPermission($user, PermissionLevel::USERS, PermissionLevel::USER_READ)) {
            return $user;
        } else {
            throw new AppException("You do not have permission to view this user.", AppException::FAIL_USER_READ_PERM);
        }
    }

    public function isUserInGroup($username, $groupName)
    {

        $groupDN = $this->getGroupDN($groupName);
        $filter = '(&(objectClass=user)(memberOf:1.2.840.113556.1.4.1941:=' . $groupDN . ')(sAMAccountName=' . $username . '))';

        $result = $this->query($filter, $this->FQDNtoDN($this->fqdn));
        //var_dump($result);
        if (is_array($result) and key_exists('count', $result) and $result['count'] > 0) {
            if (key_exists('samaccountname', $result[0])) {
                if ($result[0]['samaccountname'][0] == $username) {
                    return true;
                }
            }
        }
        return false;
    }


    public function getGroup($groupName)
    {

// var_dump($groupName);
        $studentGroupDN = $this->getGroupDN($this->district->getAdStudentGroupName());
        $staffGroupDN = $this->getGroupDN($this->district->getAdStaffGroupName());
        $filter = '(&(objectClass=group)(|(sAMAccountName=' . $groupName . ')(mail=' . $groupName . ')(description=' . $groupName . ')))';
//var_dump($filter);
        $result = $this->queryObject($filter);
        $user = \System\App\App::get()->user;
        //if ($result == false and $user->privilege >= \App\Models\user\Privilege::ADMIN) {
        //
        //     $filter = '(&(objectClass=group)(memberOf:1.2.840.113556.1.4.1941:=' . $staffGroupDN . ')(|(sAMAccountName=' . $groupName . ')(mail=' . $groupName . ')(description=' . $groupName . ')))';
//var_dump($filter);
        //    $result = $this->queryObject($filter);
        // }
        return $result;
    }


    public function getStaffGroup($groupName)
    {
        $staffGroupDN = $this->getGroupDN($this->district->getAdStaffGroupName());

        $filter = '(&(objectClass=group)(memberOf:1.2.840.113556.1.4.1941:=' . $staffGroupDN . ')(|(cn=' . $groupName . ')(mail=' . $groupName . ')(description=' . $groupName . ')))';
        return $this->queryObject($filter);
    }


    public function unlockUser($username)
    {
        $entry = ["lockoutTime" => 0];
//var_dump($this->getUserDN($username));
        ldap_mod_replace($this->connection, $this->getUserDN($username), $entry);
    }

    public function enableUser($username)
    {
        $this->setEnabledStatus($username, true);
//exit;
    }

    private function setEnabledStatus($username, $enable = true)
    {
        $filter = "(&(samaccountname=$username))";
//echo "<br><br><br>";
        $useraccountcontrol = $this->queryObject($filter)["useraccountcontrol"][0];

//var_dump($useraccountcontrol);
        $disabled = ($useraccountcontrol | 2); // set all bits plus bit 1 (=dec2)
        $enabled = ($useraccountcontrol & ~2); // set all bits minus bit 1 (=dec2)

        if ($enable) {
            if ($enabled != 1)
                $new = $enabled;
            else
                $new = $disabled; //enable or disable?
        } else {
            if ($enabled == 1)
                $new = $enabled;
            else
                $new = $disabled; //enable or disable?
        }
//var_dump($new);
        $entry = ["userAccountControl" => $new];
        ldap_mod_replace($this->connection, $this->getUserDN($username), $entry);
    }

    public function disableUser($username)
    {
        $this->setEnabledStatus($username, false);
//exit;
    }


    /**
     * Returns an array of usernames that match
     * the search term for username, first name,
     * or last name.
     *
     * @param type $searchTerm
     *
     * @return type
     */
    public function listStudentGroups($searchTerm)
    {
        $studentGroupDN = $this->getGroupDN($this->district->getAdStudentGroupName());
//var_dump($studentGroupDN);
        $filter = '(&(objectClass=group)'
            //. '(memberOf:1.2.840.113556.1.4.1941:=' . $studentGroupDN . ')'
            . '(|(sAMAccountName=*' . $searchTerm . '*)(description=*' . $searchTerm . '*)(mail=*' . $searchTerm . '*)))';
        return $this->listGroups($filter);
    }


    /**
     * Returns an array of usernames that match
     * the search term for username, first name,
     * or last name.
     *
     * @param type $searchTerm
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

    /**
     * Returns an array of usernames that match
     * the search term for username, first name,
     * or last name.
     *
     * @param type $searchTerm
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

    public function listGroupMembers($groupDN)
    {
        $filter = '(&(objectClass=user)'
            . '(memberOf:1.2.840.113556.1.4.1941:=' . $groupDN . '))';
//var_dump($filter);
        return $this->listUsers($filter);
    }

    /**
     * Returns an array of usernames that match the input filter
     * Returns false if no users were found.
     *
     * @param type $filter
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
     * @param type $filter
     *
     * @return array|false
     */
    public function listOUs($searchTerm)
    {
        $this->logger->info($searchTerm);
        $filter = '(&(objectClass=organizationalUnit)(|(name=*' . $searchTerm . '*)(ou=*' . $searchTerm . '*)))';

        //$this->logger->info($filter);
        $result = $this->query($filter);

        //$this->logger->info($result);
        //var_dump($result);
        if ($result != false) {
            if (key_exists("count", $result)) {
                foreach ($result as $ou) {
                    if (!is_int($ou)) {
                        if (key_exists('distinguishedname', $ou)) {
                            if (self::hasPermission($ou, PermissionLevel::GROUPS, PermissionLevel::GROUP_ADD)) {
                                $ous[] = $ou["distinguishedname"][0];
                            }
                        }
                    }
                }
            }
        }
        if (isset($ous)) {

//var_dump($usernames);
            sort($ous);
            return $ous;
        }
        return [];
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


    private function queryObject($filter, $baseDN = null)
    {
        $result = $this->query($filter, $baseDN);

        if ($result != false) {
            if (key_exists("count", $result) and $result["count"] == 1) {
                $user = $result[0];
//var_dump($user);
                return $user;
            }
        }
        return false;
    }

    /**
     *
     * @param type $connection
     * @param type $baseDN
     * @param type $groupName
     *
     * @return string
     */
    public function getGroupDN($groupName)
    {
        $filter = "(&(objectClass=group)(cn=" . $groupName . "))";
        $dn = $this->getObjectDN($filter);
        return $dn;
    }

    /**
     *
     * @param type $connection
     * @param type $baseDN
     * @param type $user
     *
     * @return string
     */
    public function getUserDN($user)
    {
        $filter = "(&(objectClass=person)(samaccountname=" . $user . "))";
        return $this->getObjectDN($filter);
    }

    private function getObjectDN($filter)
    {
        $result = ldap_search($this->connection, $this->baseDN, $filter);
        $info = ldap_get_entries($this->connection, $result);
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

    public function getSubOUs($dn)
    {
        if ($this->ouExists($dn)) {

            $filter = '(&(objectClass=organizationalUnit))';
            $subOUs = $this->list($filter, $dn);
//var_dump($buildingsRaw);
            return $subOUs;
        }
        return false;
    }

    public function getAllSubOUs($dn, $array = null)
    {
        if ($this->ouExists($dn) or $dn == DistrictDatabase::getAD_BaseDN(1)) {
            $filter = '(objectClass=organizationalUnit)';
            $result = $this->list($filter, $dn);
            foreach ($result as $resultEntry) {
                $ou = $resultEntry["distinguishedname"][0];
                if ($resultEntry != null and $ou != null and $ou != '' and $ou != $dn) {
                    if ($this->hasSubOUs($ou)) {
                        $ous [$ou] = $this->getAllSubOUs($ou, $array);
                    } else {
                        $ous[] = $ou;
                    }
                }
            }
            return $ous;
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

    public function setUserThumbnailPhoto($userDN, $photo)
    {
        if ($this->hasPermission($this->getOUFromDN($userDN), PermissionLevel::USERS, PermissionLevel::USER_CHANGE)) {
            $attr = ["thumbnailPhoto" => $photo];
            $this->logger->info(ldap_mod_replace($this->connection, $userDN, $attr));
            $this->logger->info(ldap_error($this->connection));
            return;
        }
        return false;
    }

}
