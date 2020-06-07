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

namespace App\Models\District;

/**
 * Description of District
 *
 * @author cjacobsen
 */

use App\Models\Model;
use System\Encryption;
use App\Models\Database\DistrictDatabase;

class District extends Model
{

    use \System\Traits\DomainTools;

    private $id;
    private $name;
    private $abbr;
    private $adFQDN;
    private $adServer;
    private $adBaseDN;
    private $adNetBIOS;
    private $adUsername;
    private $adPassword;
    private $gsFQDN;
    private $parentEmailGroup;
    private $rootOU;
    private $useTLS;

    /**
     *
     * @param type $LDAPReponse
     *
     * @return $this
     */
    public function importFromDatabase($LDAPReponse)
    {
        $this->setId($LDAPReponse["ID"])
            ->setName($LDAPReponse["Name"])
            ->setAbbr($LDAPReponse["Abbreviation"])
            ->setAdFQDN($LDAPReponse["AD_FQDN"])
            ->setAdServer($LDAPReponse["AD_Server"])
            ->setAdBaseDN($LDAPReponse["AD_BaseDN"])
            ->setAdNetBIOS($LDAPReponse["AD_NetBIOS"])
            ->setAdUsername($LDAPReponse["AD_Username"])
            ->setAdPassword(Encryption::decrypt($LDAPReponse["AD_Password"]))
            ->setGsFQDN($LDAPReponse["GA_FQDN"])
            ->setUseTLS($LDAPReponse["AD_Use_TLS"]);

        //$this->setGsFQDN($LDAPReponse["Parent_Email_Group"]);
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getGradeMin()
    {
        return $this->gradeMin;
    }

    public function setGradeMin($gradeMin)
    {
        $this->gradeMin = $gradeMin;
        return $this;
    }

    public function getGradeMax()
    {
        return $this->gradeMax;
    }

    public function setGradeMax($gradeMax)
    {
        $this->gradeMax = $gradeMax;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUseTLS()
    {
        return $this->useTLS;
    }

    /**
     * @param mixed $useTLS
     *
     * @return District
     */
    public function setUseTLS($useTLS)
    {
        $this->useTLS = $useTLS;
        return $this;
    }

    public function getAbbr()
    {
        return $this->abbr;
    }

    public function setAbbr($abbr)
    {
        $this->abbr = $abbr;
        return $this;
    }

    public function getAdFQDN()
    {
        return $this->adFQDN;
    }

    public function setAdFQDN($adFQDN)
    {
        $this->adFQDN = $adFQDN;
        return $this;
    }

    public function getAdServer()
    {
        return $this->adServer;
    }

    public function setAdServer($adServer)
    {
        $this->adServer = $adServer;
        return $this;
    }


    public function getAdNetBIOS()
    {
        return $this->adNetBIOS;
    }

    public function setAdNetBIOS($adNetBIOS)
    {
        $this->adNetBIOS = $adNetBIOS;
        return $this;
    }

    public function getAdUsername()
    {
        return $this->adUsername;
    }

    public function setAdUsername($adUsername)
    {
        $this->adUsername = $adUsername;
        return $this;
    }

    public function getAdPassword()
    {
        return $this->adPassword;
    }

    public function setAdPassword($adPassword)
    {
        $this->adPassword = $adPassword;
        return $this;
    }


    public function getGsFQDN()
    {
        return $this->gsFQDN;
    }

    public function setGsFQDN($gsFQDN)
    {
        $this->gsFQDN = $gsFQDN;
        return $this;
    }

    public function getParentEmailGroup()
    {
        return $this->parentEmailGroup;
    }

    public function setParentEmailGroup($parentEmailGroup)
    {
        $this->parentEmailGroup = $parentEmailGroup;
        return $this;
    }

    public function getSubOUs()
    {
        $ad = \App\Api\AD::get();
        $rawOUs = $ad->getSubOUs($this->getId());
        foreach ($rawOUs as $ou) {
            $ous[] = $ou['distinguishedname'];
        }
        return $ous;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getDirectoryTree()
    {
        $ad = \App\Api\AD::get();
        return $ad->getAllSubOUs($this->getRootOU());
    }

    public function getRootOU()
    {
        return $this->rootOU;
    }

    public function saveToDB()
    {
        //var_dump("saving to db");
        DistrictDatabase::setAD_FQDN(1, $this->adFQDN);
        DistrictDatabase::setAbbreviation(1, $this->abbr);
        DistrictDatabase::setADPassword(1, $this->adPassword);
        DistrictDatabase::setADUsername(1, $this->adUsername);
        DistrictDatabase::setADBaseDN(1, $this->getAdBaseDN());
        DistrictDatabase::setADNetBIOS(1, $this->adNetBIOS);
        DistrictDatabase::setName(1, $this->name);
        DistrictDatabase::setAD_UseTLS(1, $this->useTLS);
    }

    public function getAdBaseDN()
    {
        $this->logger->debug($this->adFQDN);
        if (!is_null($this->adBaseDN) and $this->adBaseDN != '') {
            return $this->adBaseDN;
        } elseif ($this->adFQDN !== null and $this->adFQDN != '') {
            return $this->FQDNtoDN($this->adFQDN);
        }
    }

    public function setAdBaseDN($adBaseDN)
    {
        $this->adBaseDN = $adBaseDN;
        $this->rootOU = $adBaseDN;
        return $this;
    }

}
