<?php

/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
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

namespace app\models\district;

/**
 * Description of User
 *
 * @author cjacobsen
 */
use app\api\AD;

class DistrictUser {

//put your code here
    public $firstName;
    public $middleName;
    public $employeeID;
    public $lastName;
    public $distinguishedName;
    public $username;
    public $enabled;
    public $street;
    public $lockedOut;
    public $description;
    public $groups;
    public $script;
    public $homeDir;
    public $homeDrv;
    public $email;
    public $adDepartment;
    public $city;
    public $state;
    public $zip;
    public $homePhone;
    public $office;
    public $adCompany;
    public $adContainerName;
    public $photo;

    /**
     *
     * @param type $adUserRaw
     */
    public function importFromAD($adUserRaw) {
        \system\app\AppLogger::get()->debug($adUserRaw);
        if (key_exists("givenname", $adUserRaw)) {
            $this->firstName = $adUserRaw["givenname"][0];
        }

        if (key_exists("thumbnailphoto", $adUserRaw)) {
            $this->photo = $adUserRaw["thumbnailphoto"][0];
        }
        if (key_exists("sn", $adUserRaw)) {
            $this->lastName = $adUserRaw["sn"][0];
        }
        if (key_exists("middlename", $adUserRaw)) {
            $this->middleName = $adUserRaw["middlename"][0];
        }
        if (key_exists("employeeid", $adUserRaw)) {
            $this->employeeID = $adUserRaw["employeeid"][0];
        }
        if (key_exists("homedirectory", $adUserRaw)) {
            $this->homeDir = $adUserRaw["homedirectory"][0];
        }
        if (key_exists("homedrive", $adUserRaw)) {
            $this->homeDrv = $adUserRaw["homedrive"][0];
        }
        if (key_exists("scriptpath", $adUserRaw)) {
            $this->script = $adUserRaw["scriptpath"][0];
        }
        if (key_exists("pager", $adUserRaw)) {
            $this->adPager = $adUserRaw["pager"][0];
        }

        if (key_exists("company", $adUserRaw)) {
            $this->adCompany = $adUserRaw["company"][0];
        }
        if (key_exists("streetaddress", $adUserRaw)) {
            $this->street = $adUserRaw["streetaddress"][0];
        }
        if (key_exists("mail", $adUserRaw)) {
            $this->email = $adUserRaw["mail"][0];
        }
        if (key_exists("description", $adUserRaw)) {
            $this->description = $adUserRaw["description"][0];
        }
        if (key_exists("samaccountname", $adUserRaw)) {
            $this->username = $adUserRaw["samaccountname"][0];
        }
        if (key_exists("displayName", $adUserRaw))
            $this->adDisplayName = $adUserRaw["displayName"][0];

        if (key_exists("department", $adUserRaw))
            $this->adDepartment = $adUserRaw["department"][0];

        if (key_exists("l", $adUserRaw))
            $this->city = $adUserRaw["l"][0];

        if (key_exists("st", $adUserRaw))
            $this->state = $adUserRaw["st"][0];

        if (key_exists("postalcode", $adUserRaw))
            $this->zip = $adUserRaw["postalcode"][0];

        if (key_exists("distinguishedname", $adUserRaw))
            $this->distinguishedName = $adUserRaw["distinguishedname"][0];

        if (key_exists("homephone", $adUserRaw))
            $this->homePhone = $adUserRaw["homephone"][0];

        if (key_exists("physicaldeliveryofficename", $adUserRaw))
            $this->office = $adUserRaw["physicaldeliveryofficename"][0];

        if (key_exists("cn", $adUserRaw))
            $this->adContainerName = $adUserRaw["cn"][0];

        if (key_exists("memberof", $adUserRaw)) {
            foreach ($adUserRaw["memberof"] as $key => $memberGroup) {
                if (strval($key) != "count") {
                    $groups[] = $memberGroup;
                }
            }

            $this->groups = $groups;
        }

        if (key_exists("lockouttime", $adUserRaw)) {
            if (intval($adUserRaw["lockouttime"][0]) > 0) {
                $this->lockedOut = true;
            } else {
                $this->lockedOut = false;
            }
        } else {
            $this->lockedOut = false;
        }
        if (key_exists("enabled", $adUserRaw)) {
            $this->enabled = $adUserRaw['enabled'];
        }
    }

    /**
     * @deprecated since version number
     * @param \Google_Service_Directory_User $gaUserRaw
     */
    private function processGA($gaUserRaw) {
        /* @var $gaUserRaw \Google_Service_Directory_User */
        $this->gaFirstName = $gaUserRaw->getName()->getGivenName();

        $this->gaLastName = $gaUserRaw->getName()->getFamilyName();

        $this->gaEnabled = !$gaUserRaw->getSuspended();

        $this->gaUsername = $gaUserRaw->getEmails()[0]['address'];
        $googleGroups = \app\api\GAM::get()->getUserGroups($this->gaUsername);
        foreach ($googleGroups as $group) {
            $this->gaGroups[$group["name"]] = $group["email"];
        }
    }

    public function getFullName() {
        $middle = ' ';
        if ($this->getMiddleName() != '') {
            $middle .= $this->getMiddleName() . ' ';
        }

        return $this->getFirstName() . $middle . $this->getLastName();
    }

    public function getDistinguishedName() {
        return $this->distinguishedName;
    }

    public function getHomeDrv() {
        return $this->homeDrv;
    }

    public function setDistinguishedName($distinguishedName) {
        $this->distinguishedName = $distinguishedName;
        return $this;
    }

    public function setHomeDrv($homeDrv) {
        $this->homeDrv = $homeDrv;
        return $this;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getMiddleName() {
        return $this->middleName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEnabled() {
        return $this->enabled;
    }

    function getAdDepartment() {
        return $this->adDepartment;
    }

    function setAdDepartment($adDepartment): void {
        $this->adDepartment = $adDepartment;
    }

    function getDescription() {
        return $this->description;
    }

    function getPhoto() {
        return $this->photo;
    }

    function setPhoto($photo): void {
        $this->photo = $photo;
    }

    function setDescription($description): void {
        $this->description = $description;
    }

    public function getGaEnabled() {
        return $this->gaEnabled;
    }

    public function getGroups() {
        return $this->groups;
    }

    public function getScript() {
        return $this->script;
    }

    public function getHomeDir() {
        return $this->homeDir;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getGaUsername() {
        return $this->gaUsername;
    }

    public function getGaGroups() {
        return $this->gaGroups;
    }

    /**
     *
     * @param type $firstName
     * @return self
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     *
     * @param type $middleName
     * @return self
     */
    public function setMiddleName($middleName) {
        $this->middleName = $middleName;
        return $this;
    }

    function getAdContainerName() {
        return $this->adContainerName;
    }

    function setAdContainerName($adContainerName) {
        $this->adContainerName = $adContainerName;
        return $this;
    }

    function getAdCompany() {
        return $this->adCompany;
    }

    function setAdCompany($adCompany) {
        $this->adCompany = $adCompany;
        return $this;
    }

    /**
     *
     * @param type $lastName
     * @return self
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     *
     * @param type $username
     * @return self
     */
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    function getOffice() {
        return $this->office;
    }

    function setOffice($office) {
        $this->office = $office;
        return $this;
    }

    function getCity() {
        return $this->city;
    }

    function getState() {
        return $this->state;
    }

    function getZip() {
        return $this->zip;
    }

    function setCity($city) {
        $this->city = $city;
        return $this;
    }

    function setState($state) {
        $this->state = $state;
        return $this;
    }

    function setZip($zip) {
        $this->zip = $zip;
        return $this;
    }

    function getStreet() {
        return $this->street;
    }

    function setStreet($street) {
        $this->street = $street;
        return $this;
    }

    function getHomePhone() {
        return $this->homePhone;
    }

    function setHomePhone($homePhone) {
        $this->homePhone = $homePhone;
        return $this;
    }

    function getEmployeeID() {
        return $this->employeeID;
    }

    function setEmployeeID($employeeID) {
        $this->employeeID = $employeeID;
        return $this;
    }

    function getLockedOut() {
        return $this->lockedOut;
    }

    function setEnabled($enabled) {
        $this->enabled = $enabled;
        return $this;
    }

    function setLockedOut($lockedOut) {
        $this->lockedOut = $lockedOut;
        return $this;
    }

    /**
     *
     * @param type $adEnabled
     * @return self
     */
    public function setAdEnabled($adEnabled) {
        $this->enabled = $adEnabled;
        return $this;
    }

    /**
     *
     * @param type $gaEnabled
     * @return self
     */
    public function setGaEnabled($gaEnabled) {
        $this->gaEnabled = $gaEnabled;
        return $this;
    }

    /**
     *
     * @param type $groups
     * @return self
     */
    public function setGroups($groups) {
        $this->groups = $groups;
        return $this;
    }

    /**
     *
     * @param type $script
     * @return self
     */
    public function setScript($script) {
        $this->script = $script;
        return $this;
    }

    /**
     *
     * @param type $homeDir
     * @return self
     */
    public function setHomeDir($homeDir) {
        $this->homeDir = $homeDir;
        return $this;
    }

    /**
     *
     * @param type $email
     * @return self
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     *
     * @param type $gaUsername
     * @return self
     */
    public function setGaUsername($gaUsername) {
        $this->gaUsername = $gaUsername;
        return $this;
    }

    /**
     *
     * @param type $gaGroups
     * @return self
     */
    public function setGaGroups($gaGroups) {
        $this->gaGroups = $gaGroups;
        return $this;
    }

    public function unlock() {
        $result = AD::get()->unlockUser($this->username);
    }

    public function disable() {
        $result = AD::get()->disableUser($this->username);
    }

    public function enable() {
        $result = AD::get()->enableUser($this->username);
    }

    public function getOu() {
        return substr($this->distinguishedName, strpos($this->distinguishedName, ',') + 1);
    }

}
