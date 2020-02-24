<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\district;

/**
 * Description of User
 *
 * @author cjacobsen
 */
class User {

    //put your code here
    private $adFirstName;
    private $adMiddleName;
    private $adEmployeeID;
    private $adLastName;
    private $adUsername;
    private $adEnabled;
    private $adStreet;
    private $adLockedOut;
    private $adDescription;
    private $adGroups;
    private $adScript;
    private $adHomeDir;
    private $adHomeDrv;
    private $adEmail;

    /**
     *
     * @param type $adUserRaw
     * @param type $gaUserRaw
     */
    function __construct($username) {
        /* @var $gaUserRaw \Google_Service_Directory_User */
        //var_dump($adUserRaw);

        $adUserRaw = \app\api\AD::get()->getStaffUser($username);
        $this->processAD($adUserRaw);
    }

    /**
     *
     * @param type $adUserRaw
     */
    private function processAD($adUserRaw) {
        if (key_exists("givenname", $adUserRaw)) {
            $this->adFirstName = $adUserRaw["givenname"][0];
        }
        if (key_exists("sn", $adUserRaw)) {
            $this->adLastName = $adUserRaw["sn"][0];
        }
        if (key_exists("middlename", $adUserRaw)) {
            $this->adMiddleName = $adUserRaw["middlename"][0];
        }
        if (key_exists("employeeid", $adUserRaw)) {
            $this->adEmployeeID = $adUserRaw["employeeid"][0];
        }
        if (key_exists("homedirectory", $adUserRaw)) {
            $this->adHomeDir = $adUserRaw["homedirectory"][0];
        }
        if (key_exists("homedrive", $adUserRaw)) {
            $this->adHomeDrv = $adUserRaw["homedrive"][0];
        }
        if (key_exists("scriptpath", $adUserRaw)) {
            $this->adScript = $adUserRaw["scriptpath"][0];
        }
        if (key_exists("pager", $adUserRaw)) {
            $this->adPager = $adUserRaw["pager"][0];
        }
        if (key_exists("scriptpath", $adUserRaw)) {
            $this->adScript = $adUserRaw["scriptpath"][0];
        }
        if (key_exists("company", $adUserRaw)) {
            $this->adCompany = $adUserRaw["company"][0];
        }
        if (key_exists("streetaddress", $adUserRaw)) {
            $this->adStreet = $adUserRaw["streetaddress"][0];
        }
        if (key_exists("mail", $adUserRaw)) {
            $this->adEmail = $adUserRaw["mail"][0];
        }
        if (key_exists("description", $adUserRaw)) {
            $this->adDescription = $adUserRaw["description"][0];
        }
        if (key_exists("samaccountname", $adUserRaw)) {
            $this->adUsername = $adUserRaw["samaccountname"][0];
        }
        if (key_exists("displayName", $adUserRaw)) {
            $this->adDisplayName = $adUserRaw["displayName"][0];
        }
        if (key_exists("department", $adUserRaw)) {
            $this->adDepartment = $adUserRaw["department"][0];
        }

        if (key_exists("cn", $adUserRaw)) {
            $this->adCommonName = $adUserRaw["cn"][0];
        }
        if (key_exists("memberof", $adUserRaw)) {
            foreach ($adUserRaw["memberof"] as $key => $memberGroup) {
                if (strval($key) != "count") {
                    $groups[] = $memberGroup;
                }
            }

            $this->adGroups = $groups;
        }

        if (key_exists("lockouttime", $adUserRaw)) {
            if (intval($adUserRaw["lockouttime"][0]) > 0) {
                $this->adLockedOut = true;
            } else {
                $this->adLockedOut = false;
            }
        } else {
            $this->adLockedOut = false;
        }
        $this->adEnabled = $adUserRaw['enabled'];
    }

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

    public function getADFirstName() {
        return $this->adFirstName;
    }

    public function getADMiddleName() {
        return $this->adMiddleName;
    }

    public function getLastName() {
        return $this->adLastName;
    }

    public function getAdUsername() {
        return $this->adUsername;
    }

    public function getAdEnabled() {
        return $this->adEnabled;
    }

    public function getGaEnabled() {
        return $this->gaEnabled;
    }

    public function getAdGroups() {
        return $this->adGroups;
    }

    public function getAdScript() {
        return $this->adScript;
    }

    public function getAdHomeDir() {
        return $this->adHomeDir;
    }

    public function getAdEmail() {
        return $this->adEmail;
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
        $this->adFirstName = $firstName;
        return $this;
    }

    /**
     *
     * @param type $middleName
     * @return self
     */
    public function setMiddleName($middleName) {
        $this->adMiddleName = $middleName;
        return $this;
    }

    /**
     *
     * @param type $lastName
     * @return self
     */
    public function setLastName($lastName) {
        $this->adLastName = $lastName;
        return $this;
    }

    /**
     *
     * @param type $adUsername
     * @return self
     */
    public function setAdUsername($adUsername) {
        $this->adUsername = $adUsername;
        return $this;
    }

    /**
     *
     * @param type $adEnabled
     * @return self
     */
    public function setAdEnabled($adEnabled) {
        $this->adEnabled = $adEnabled;
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
     * @param type $adGroups
     * @return self
     */
    public function setAdGroups($adGroups) {
        $this->adGroups = $adGroups;
        return $this;
    }

    /**
     *
     * @param type $adScript
     * @return self
     */
    public function setAdScript($adScript) {
        $this->adScript = $adScript;
        return $this;
    }

    /**
     *
     * @param type $adHomeDir
     * @return self
     */
    public function setAdHomeDir($adHomeDir) {
        $this->adHomeDir = $adHomeDir;
        return $this;
    }

    /**
     *
     * @param type $adEmail
     * @return self
     */
    public function setAdEmail($adEmail) {
        $this->adEmail = $adEmail;
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

}
