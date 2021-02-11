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
 * Description of Group
 *
 * @author cjacobsen
 */


use Adldap\Models\Group;
use Adldap\Models\User;
use App\Api\Ad\ADConnection;
use App\Api\Ad\ADGroups;
use App\Api\Ad\ADUsers;
use App\Models\User\PermissionHandler;
use App\Models\User\PermissionLevel;
use System\App\AppException;
use System\Traits\DomainTools;


class DomainGroup extends ADModel
{
    use DomainTools;

    /**
     * @var \Adldap\Models\Group;
     */
    public $activeDirectory;
    public $id;

    /**
     * DistrictUser constructor.
     *
     * @param User $group
     */
    public function __construct($group = null)
    {
        parent::__construct();

        $this->logger->debug("Searching for group named: " . $group);
        $this->activeDirectory = ADGroups::getGroup($group);
        $this->logger->debug($this);
        //$this->logger->debug(bin2hex($this->activeDirectory->getObjectSid()));
        $this->id = (bin2hex($this->activeDirectory->getObjectGuid()));
        if (!PermissionHandler::hasPermission(self::getOUFromDN($this->activeDirectory->getDistinguishedName()), PermissionLevel::GROUPS, PermissionLevel::GROUP_READ)) {
            throw new AppException('That group was not found.', AppException::FAIL_GROUP_READ_PERM);

        }
    }

    public function getEmail()
    {
        return $this->getAttribute("mail");
    }

    public function getParents()
    {
        $parents = [];
        foreach ($this->activeDirectory->getMemberOf() as $parent) {
            $parents[] = ADGroups::getGroupByDN($parent)->getName();
        }
        return $parents;
    }

    public function getMembers()
    {
        $members = [];
        foreach ($this->activeDirectory->getMembers() as $groupMember) {
            if ($groupMember instanceof User) {
                $this->logger->debug($groupMember);
                try {
                    $members[] = new DomainUser($groupMember);
                } catch (AppException $exception) {
                    $this->logger->warning($exception);
                }
            }
        }
        return $members;
    }

    public function getChildren()
    {
        $children = [];
        foreach (ADGroups::getChildren($this->activeDirectory->getDn()) as $subGroup) {


            if ($subGroup instanceof Group) {
                $this->logger->debug($subGroup);
                try {
                    $children[] = new DomainGroup($subGroup->getName());
                } catch (AppException $exception) {
                    $this->logger->warning($exception);
                }
            }
        }
        return $children;
    }


    public function addMember($distinguishedName)
    {

        if (!$this->hasMember($distinguishedName)) {
            return $this->activeDirectory->addMember($distinguishedName);
        } else {
            throw new AppException('User already in group');
        }
    }

    public function hasMember($distinguishedName)
    {
        /* @var $member User */
        $this->logger->debug($this->activeDirectory->getMembers());
        foreach ($this->activeDirectory->getMembers() as $member) {
            $this->logger->debug("Checking for matching Group Member: " . $distinguishedName . ' -> ' . $member->getDn());
            if ($member->getDn() == $distinguishedName) {
                $this->logger->debug("Found Match: " . $member);
                if ($member instanceof User) {
                    return new DomainUser($member);
                } elseif ($member instanceof Group) {
                    return new DomainGroup($member->getAccountName());
                }
            }
        }
        return false;
    }

    public function getOU()
    {
        return self::getOUFromDN($this->activeDirectory->getDistinguishedName());
    }

    public function getDistinguishedName()
    {
        if (is_null($this->activeDirectory)) {
            return null;
        }
        return $this->activeDirectory->getDn();
    }

}
