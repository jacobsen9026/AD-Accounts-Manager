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
 * Description of Group
 *
 * @author cjacobsen
 */
use app\models\district\DistrictUser;

class Group {

    private $distinguishedName;
    private $name;
    private $email;

    /**
     *
     * @var DistrictUser
     */
    private $members;

    public function getDistinguishedName() {
        return $this->distinguishedName;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getMembers() {
        return $this->members;
    }

    public function setDistinguishedName($distinguishedName) {
        $this->distinguishedName = $distinguishedName;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setMembers(array $members) {
        $this->members = $members;
        return $this;
    }

    public function hasMember($member) {
        if (is_string($member)) {
            foreach ($this->getMembers() as $mem) {
                if ($member == $mem->getUsername()) {
                    return $mem;
                }
            }
            return false;
        } elseif (is_object($member) and get_class($member) == DistrictUser::class) {
            foreach ($this->getMembers() as $mem) {
                if ($member->getUsername() == $mem->getUsername()) {
                    return $mem;
                }
            }
            return false;
        }
        return false;
    }

    public function fillMembers() {
        $ad = \app\api\AD::get();
        $members = array();
        $members = $ad->listGroupMembers($this->distinguishedName);

        $users = array();
        foreach ($members as $member) {
            $user = new DistrictUser();
            $user->importFromAD($ad->searchUser($member));
            $users[] = $user;
        }
        $this->setMembers($users);
        return $this;
//var_dump($this);
    }

    public function importFromAD($rawADResponse) {
//var_dump($rawADResponse);
        $this->setDistinguishedName($rawADResponse['distinguishedname'][0]);
        if (key_exists('mail', $rawADResponse))
            $this->setEmail($rawADResponse['mail']);
        if (key_exists('name', $rawADResponse))
            $this->setName($rawADResponse['name'][0]);
        $this->fillMembers();
    }

    public function removeMember(DistrictUser $user) {


        $ad = \app\api\AD::get();
        return $ad->removeUserFromGroup($this->distinguishedName, $user->getDistinguishedName());
    }

}
