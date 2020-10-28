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
 * Description of user
 *
 * @author cjacobsen
 */

use Adldap\Models\User;
use App\Api\Ad\ADUsers;
use App\Models\User\PermissionHandler;
use App\Models\User\PermissionLevel;
use System\App\AppException;
use System\App\LDAPLogger;
use System\Traits\DomainTools;

class DistrictUser extends ADModel
{
    /**
     * Include the DomainTools trait
     */
    use DomainTools;


    /**
     * @var User
     */
    public $activeDirectory;


    /**
     * DistrictUser constructor.
     *
     * @param User|string $user Provide either the users samaccountname or the already retrieved ADLDAP2 user object
     *
     * @throws \System\App\AppException
     */
    public function __construct($user)
    {
        parent::__construct();
        if (is_string($user)) {
            $this->activeDirectory = ADUsers::getDomainScopUser($user);
        } elseif ($user instanceof User) {
            $this->activeDirectory = $user;
        }
        if (!PermissionHandler::hasPermission(self::getOUFromDN($this->activeDirectory->getDistinguishedName()), PermissionLevel::USERS, PermissionLevel::USER_READ)) {
            throw new AppException('That user was not found.', AppException::FAIL_USER_READ_PERM);

        }
    }

    /**
     * Get the OU that the user is in
     *
     * @return string
     */
    public function getOU()
    {
        return self::getOUFromDN($this->activeDirectory->getDistinguishedName());
    }

    /**
     * Get the city in the Address tab of AD
     *
     * @return string
     */
    public function getCity()
    {
        return $this->getAttribute('l');
    }

    /**
     * Get State from Address tab in AD ("st")
     *
     * @return string
     */
    public function getState()
    {
        return $this->getAttribute('st');
    }

    /**
     * Unlock the user
     *
     * @return bool
     */
    public function unlock()
    {
        $modifiedUser = $this->activeDirectory->setClearLockoutTime();
        $this->logger->debug($modifiedUser);
        return $modifiedUser->save();

    }

    /**
     * Disable the user
     */
    public function disable()
    {
        $this->setEnabledStatus(0);
    }

    /**
     * Sets a user to enabled or disabled in Active Directory
     *
     * @param bool $enable If ommitted, will be true;
     *
     * @return bool
     */
    private function setEnabledStatus($enable = true): bool
    {
        /**
         * Get the existing user account control
         */
        $useraccountcontrol = $this->activeDirectory->getUserAccountControl();
        /**
         * Prepare enabled and disabled forms, one
         * of these will be invalid depending on
         * current user enabled state
         */
        $disabled = ($useraccountcontrol | 2); // set all bits plus bit 1 (=dec2)
        $enabled = ($useraccountcontrol & ~2); // set all bits minus bit 1 (=dec2)

        /**
         * Choose which new account control value to use
         */
        if ($enable) {
            if (1 !== $enabled)
                $new = $enabled;
            else
                $new = $disabled; //enable or disable?
        } else {
            if (1 == $enabled)
                $new = $enabled;
            else
                $new = $disabled; //enable or disable?
        }
        //var_dump($this);
        $modifiedUser = $this->activeDirectory->setUserAccountControl($new);
        $this->logger->debug($modifiedUser);
        return $modifiedUser->save();
    }

    public function enable()
    {
        $this->setEnabledStatus(1);
    }

    /**
     * Gets the full name in (First [Middle] Last) format
     *
     * @return string
     */
    public function getFullName()
    {
        $middle = " ";
        if ($this->getMiddleName() != null) {
            $middle .= $this->getMiddleName() . " ";
        }
        $fullname = $this->activeDirectory->getFirstName() . $middle . $this->activeDirectory->getLastName();
        return $fullname;
    }

    /**
     * Get the user's middle name
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->getAttribute('middlename');
    }

    public function isLockedOut()
    {
        $this->logger->debug($this->activeDirectory->getLockoutTime());
        if ($this->activeDirectory->getLockoutTime() === '0' || $this->activeDirectory->getLockoutTime() ==="" || $this->activeDirectory->getLockoutTime()===null ) {
            return false;
        }
        return true;
    }

    public function isDisabled()
    {
        return $this->activeDirectory->isDisabled();
    }

    public function isActive()
    {
        return $this->activeDirectory->isActive();
    }

    public function getEmployeeId()
    {
        return $this->activeDirectory->getEmployeeId();
    }

    public function getFirstName()
    {
        return $this->activeDirectory->getFirstName();
    }

    public function getLastName()
    {
        return $this->activeDirectory->getLastName();
    }

    public function getHomePhone()
    {
        return $this->activeDirectory->getHomePhone();
    }

    public function getStreetAddress()
    {
        return $this->activeDirectory->getStreetAddress();
    }

    public function getPostalCode()
    {
        return $this->activeDirectory->getPostalCode();
    }

    public function getPOBox()
    {
        return $this->activeDirectory->getPostOfficeBox();
    }

    public function getDepartment()
    {
        return $this->activeDirectory->getDepartment();
    }

    public function getDescription()
    {
        return $this->activeDirectory->getDescription();
    }

    public function getOfficeName()
    {
        return $this->activeDirectory->getPhysicalDeliveryOfficeName();
    }

    public function getCompany()
    {
        return $this->activeDirectory->getCompany();
    }

    public function getUsername()
    {
        return $this->activeDirectory->getAccountName();
    }

    public function getEmail()
    {
        return $this->activeDirectory->getEmail();
    }

    public function getGroups()
    {
        $allowedGroups = [];
        $groups = $this->activeDirectory->getGroups();
        foreach ($groups as $group) {
            if (PermissionHandler::hasPermission(self::getOUFromDN($group->getDistinguishedName()), PermissionLevel::GROUPS, PermissionLevel::GROUP_READ)) {
                //throw new AppException('That user was not found.', AppException::FAIL_GROUP_READ_PERM);
                $allowedGroups[] = $group;
            }
        }
        return $allowedGroups;
    }

    public function getDistinguishedName()
    {
        return $this->activeDirectory->getDistinguishedName();
    }


}