<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\district;

/**
 * Description of ADUser
 *
 * @author cjacobsen
 */
class ADUser {

    //put your code here
    private $fullName;
    private $firstName;
    private $lastName;
    private $street;
    private $description;
    private $groups;
    private $id;
    private $hdir;
    private $hdrv;
    private $script;
    private $lockedOut;
    private $enabled;
    private $middleName;

    public function getFullName() {
        return $this->fullName;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getStreet() {
        return $this->street;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getGroups() {
        return $this->groups;
    }

    public function getId() {
        return $this->id;
    }

    public function getHdir() {
        return $this->hdir;
    }

    public function getHdrv() {
        return $this->hdrv;
    }

    public function getScript() {
        return $this->script;
    }

    public function getLockedOut() {
        return $this->lockedOut;
    }

    public function getEnabled() {
        return $this->enabled;
    }

    public function getMiddleName() {
        return $this->middleName;
    }

    public function setMiddleName($middleName) {
        $this->middleName = $middleName;
        return $this;
    }

    public function setFullName($fullName) {
        $this->fullName = $fullName;
        return $this;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
        return $this;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
        return $this;
    }

    public function setStreet($street) {
        $this->street = $street;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setGroups($groups) {
        $this->groups = $groups;
        return $this;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setHdir($hdir) {
        $this->hdir = $hdir;
        return $this;
    }

    public function setHdrv($hdrv) {
        $this->hdrv = $hdrv;
        return $this;
    }

    public function setScript($script) {
        $this->script = $script;
        return $this;
    }

    public function setLockedOut($lockedOut) {
        $this->lockedOut = $lockedOut;
        return $this;
    }

    public function setEnabled($enabled) {
        $this->enabled = $enabled;
        return $this;
    }

}
