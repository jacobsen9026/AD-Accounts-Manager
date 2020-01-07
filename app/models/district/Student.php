<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\district;

/**
 * Description of Student
 *
 * @author cjacobsen
 */
class Student {

    //put your code here
    private $firstName;
    private $middleName;
    private $lastName;
    private $adUsername;
    private $adEnabled;
    private $gaEnabled;
    private $adGroups;
    private $adScript;
    private $adHomeDir;
    private $adEmail;
    private $gaUsername;
    private $gaGroups;

    public function getFirstName() {
        return $this->firstName;
    }

    public function getMiddleName() {
        return $this->middleName;
    }

    public function getLastName() {
        return $this->lastName;
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
