<?php


namespace App\Models\District;


use App\Api\Ad\ADGroups;
use App\Models\District\DirectoryGroup;

class AddDistrictGroup
{

    protected $newGroupName;
    protected $newGroupEmail;
    protected $newGroupDescription;
    protected $newGroupOU;
    protected $newGroupDN;


    /**
     * @return mixed
     */
    public function getNewGroupName()
    {
        return $this->newGroupName;
    }

    /**
     * @param mixed $newGroupName
     *
     * @return AddDistrictGroup
     */
    public function setNewGroupName($newGroupName)
    {
        $this->newGroupName = $newGroupName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewGroupEmail()
    {
        return $this->newGroupEmail;
    }

    /**
     * @param mixed $newGroupEmail
     *
     * @return AddDistrictGroup
     */
    public function setNewGroupEmail($newGroupEmail)
    {
        $this->newGroupEmail = $newGroupEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewGroupDescription()
    {
        return $this->newGroupDescription;
    }

    /**
     * @param mixed $newGroupDescription
     *
     * @return AddDistrictGroup
     */
    public function setNewGroupDescription($newGroupDescription)
    {
        $this->newGroupDescription = $newGroupDescription;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewGroupOU()
    {
        return $this->newGroupOU;
    }

    /**
     * @param mixed $newGroupOU
     *
     * @return AddDistrictGroup
     */
    public function setNewGroupOU($newGroupOU)
    {
        $this->newGroupOU = $newGroupOU;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewGroupDN()
    {
        return $this->newGroupDN;
    }

    /**
     * @param mixed $newGroupDN
     *
     * @return AddDistrictGroup
     */
    public function setNewGroupDN($newGroupDN)
    {
        $this->newGroupDN = $newGroupDN;
        return $this;
    }

    public function createGroup()
    {
        ADGroups::createGroup($this);
    }


}