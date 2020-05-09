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
 * Description of District
 *
 * @author cjacobsen
 */
use app\models\Model;
use system\Encryption;
use app\models\database\DistrictDatabase;

class District extends Model {

    use \system\traits\DomainTools;

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
    private $adStaffGroupName;
    private $gsFQDN;
    private $parentEmailGroup;
    private $rootOU;

    /**
     *
     * @param type $LDAPReponse
     * @return $this
     */
    public function importFromDatabase($LDAPReponse) {
        $this->setId($LDAPReponse["ID"])
                ->setName($LDAPReponse["Name"])
                ->setGradeMin($LDAPReponse["Grade_Span_From"])
                ->setGradeMax($LDAPReponse["Grade_Span_To"])
                ->setAbbr($LDAPReponse["Abbreviation"])
                ->setAdFQDN($LDAPReponse["AD_FQDN"])
                ->setAdServer($LDAPReponse["AD_Server"])
                ->setAdBaseDN($LDAPReponse["AD_BaseDN"])
                ->setAdNetBIOS($LDAPReponse["AD_NetBIOS"])
                ->setAdUsername($LDAPReponse["AD_Username"])
                ->setAdPassword(Encryption::decrypt($LDAPReponse["AD_Password"]))
                ->setAdStudentGroupName($LDAPReponse["AD_Student_Group"])
                ->setAdStaffGroupName($LDAPReponse["AD_Staff_Group"])
                ->setGsFQDN($LDAPReponse["GA_FQDN"]);
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
        if (is_null($this->adBaseDN)or $this->adBaseDN == '' and $this->adFQDN != '') {
            return $this->FQDNtoDN($this->adFQDN);
        } else {
            return $this->adBaseDN;
        }
    }

    public function getAdStaffGroupName() {
        return $this->adStaffGroupName;
    }

    public function setAdStaffGroupName($adStaffGroupName) {
        $this->adStaffGroupName = $adStaffGroupName;
        return $this;
    }

    public function getRootOU() {
        return $this->rootOU;
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
        $this->rootOU = $adBaseDN;
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

    public function getSubOUs() {
        $ad = \app\api\AD::get();
        $rawOUs = $ad->getSubOUs($this->getId());
        foreach ($rawOUs as $ou) {
            $ous[] = $ou['distinguishedname'];
        }
        return $ous;
    }

    public function getDirectoryTree() {
        $ad = \app\api\AD::get();
        return $ad->getAllSubOUs($this->getRootOU());
    }

    public function saveToDB() {
        //var_dump("saving to db");
        DistrictDatabase::setAD_FQDN(1, $this->adFQDN);
        DistrictDatabase::setADPassword(1, $this->adPassword);
        DistrictDatabase::setADUsername(1, $this->adUsername);
        DistrictDatabase::setADBaseDN(1, $this->getAdBaseDN());
        DistrictDatabase::setADStudentGroup(1, $this->adStudentGroupName);
        DistrictDatabase::setADStaffGroup(1, $this->adStaffGroupName);
        DistrictDatabase::setName(1, $this->name);
    }

}
