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


use App\Api\Ad\ADGroups;


class DistrictGroup extends ADModel
{
    /**
     * @var \Adldap\Models\Group;
     */
    public $activeDirectory;

    /**
     * DistrictUser constructor.
     *
     * @param User $group
     */
    public function __construct($group)
    {
        var_dump($group);
        if (is_string($group)) {
            $this->activeDirectory = ADGroups::getGroup($group);

        } elseif ($group instanceof User) {
            $this->activeDirectory = $group;
        }
    }

    public function getEmail()
    {
        return $this->getAttribute("mail");
    }

    public function getMembers()
    {
        $members = [];
        foreach ($this->activeDirectory->getMembers() as $groupMember) {
            $members[] = new DistrictUser($groupMember);
        }
        return $members;
    }

    public function hasMember($username)
    {
        /* @var $member \Adldap\Models\User */

        foreach ($this->activeDirectory->getMemberNames() as $member) {
            if ($member == $username) {
                return new DistrictUser($member);
            }
        }
        return false;
    }

}
