<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\district;

/**
 * Description of District
 *
 * @author cjacobsen
 */
use app\models\DatabaseModel;

class District extends DatabaseModel {

    private $id;
    private $name;
    private $gradeMin;
    private $gradeMax;
    private $abbr;
    private $adFQDN;
    private $adServer;
    private $adBaseDN;
    private $adNetBIOS;
    private $adUsername;
    private $adPassword;
    private $adStudentGroupName;
    private $gsFQDN;
    private $parentEmailGroup;

    /**
     *
     * @param type $LDAPReponse
     * @return $this
     */
    public function importFromAD($LDAPReponse) {
        $this->setId($LDAPReponse["ID"]);
        $this->setName($LDAPReponse["Name"]);
        $this->setGradeMin($LDAPReponse["Grade_Span_From"]);
        $this->setGradeMax($LDAPReponse["Grade_Span_To"]);
        $this->setAbbr($LDAPReponse["Abbreviation"]);
        $this->setAdFQDN($LDAPReponse["AD_FQDN"]);
        $this->setAdServer($LDAPReponse["AD_Server"]);
        $this->setAdBaseDN($LDAPReponse["AD_BaseDN"]);
        $this->setAdNetBIOS($LDAPReponse["AD_NetBIOS"]);
        $this->setAdUsername($LDAPReponse["AD_Username"]);
        $this->setAdPassword($LDAPReponse["AD_Password"]);
        $this->setAdStudentGroupName($LDAPReponse["AD_Student_Group"]);
        $this->setGsFQDN($LDAPReponse["GA_FQDN"]);
        //$this->setGsFQDN($LDAPReponse["Parent_Email_Group"]);
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getGradeMin() {
        return $this->gradeMin;
    }

    public function getGradeMax() {
        return $this->gradeMax;
    }

    public function getAbbr() {
        return $this->abbr;
    }

    public function getAdFQDN() {
        return $this->adFQDN;
    }

    public function getAdServer() {
        return $this->adServer;
    }

    public function getAdBaseDN() {
        return $this->adBaseDN;
    }

    public function getAdNetBIOS() {
        return $this->adNetBIOS;
    }

    public function getAdUsername() {
        return $this->adUsername;
    }

    public function getAdPassword() {
        return $this->adPassword;
    }

    public function getAdStudentGroupName() {
        return $this->adStudentGroupName;
    }

    public function getGsFQDN() {
        return $this->gsFQDN;
    }

    public function getParentEmailGroup() {
        return $this->parentEmailGroup;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setGradeMin($gradeMin) {
        $this->gradeMin = $gradeMin;
        return $this;
    }

    public function setGradeMax($gradeMax) {
        $this->gradeMax = $gradeMax;
        return $this;
    }

    public function setAbbr($abbr) {
        $this->abbr = $abbr;
        return $this;
    }

    public function setAdFQDN($adFQDN) {
        $this->adFQDN = $adFQDN;
        return $this;
    }

    public function setAdServer($adServer) {
        $this->adServer = $adServer;
        return $this;
    }

    public function setAdBaseDN($adBaseDN) {
        $this->adBaseDN = $adBaseDN;
        return $this;
    }

    public function setAdNetBIOS($adNetBIOS) {
        $this->adNetBIOS = $adNetBIOS;
        return $this;
    }

    public function setAdUsername($adUsername) {
        $this->adUsername = $adUsername;
        return $this;
    }

    public function setAdPassword($adPassword) {
        $this->adPassword = $adPassword;
        return $this;
    }

    public function setAdStudentGroupName($adStudentGroupName) {
        $this->adStudentGroupName = $adStudentGroupName;
        return $this;
    }

    public function setGsFQDN($gsFQDN) {
        $this->gsFQDN = $gsFQDN;
        return $this;
    }

    public function setParentEmailGroup($parentEmailGroup) {
        $this->parentEmailGroup = $parentEmailGroup;
        return $this;
    }

    public function saveToDB() {

    }

}
