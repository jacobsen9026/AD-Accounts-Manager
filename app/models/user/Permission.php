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

namespace App\Models\User;

/**
 * Description of Permission
 *
 * @author cjacobsen
 */
use App\Models\Model;
use App\Models\Database\PermissionMapDatabase;
use System\Encryption;

class Permission extends Model {

    private $id;
    private $refID;
    private $ou;
    private $privilegeID;
    private $scopeColumn;
    private $scopeID;
    private $userPermissionLevel;
    private $groupPermissionLevel;

    public function getRefID() {
        return $this->privilegeID . hash("sha256", $this->getOu());
    }

    public function getId() {
        return $this->id;
    }

    public function getGroupName() {
        $privilege = new PrivilegeLevel();
        $privilege->importFromDatabase(\App\Models\Database\PrivilegeLevelDatabase::get($this->getPrivilegeID()));
        return $privilege->getAdGroup();
    }

    public function getOu() {
        return $this->ou;
    }

    public function setOu($ou) {
        $this->ou = $ou;
        $this->ouDepth = count(explode('OU=', $ou));
        return $this;
    }

    public function getPrivilegeID() {
        return $this->privilegeID;
    }

    public function getScopeColumn() {
        return $this->scopeColumn;
    }

    public function getScopeID() {
        return $this->scopeID;
    }

    public function getType() {
        return $this->type;
    }

    public function getUserPermissionLevel() {
        return $this->userPermissionLevel;
    }

    public function getGroupPermissionLevel() {
        return $this->groupPermissionLevel;
    }

    public function setUserPermissionLevel($userPermissionLevel) {
        $this->logger->info("Setting user permission level to " . $userPermissionLevel);
        $this->userPermissionLevel = $userPermissionLevel;
        return $this;
    }

    public function setGroupPermissionLevel($groupPermissionLevel) {
        $this->groupPermissionLevel = $groupPermissionLevel;
        return $this;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setPrivilegeID($privilegeID) {
        $this->privilegeID = $privilegeID;
        return $this;
    }

    public function setScopeColumn($scopeColumn) {
        $this->scopeColumn = $scopeColumn;
        return $this;
    }

    public function setScopeID($scopeID) {
        $this->scopeID = $scopeID;
        return $this;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function getOuDepth() {
        return $this->ouDepth;
    }

    public function setOuDepth($ouDepth) {
        $this->ouDepth = $ouDepth;
        return $this;
    }

    public function importFromDatabase($rawDBResponse) {
        //var_dump($rawDBResponse);

        $this->setId($rawDBResponse['ID'])
                ->setOu($rawDBResponse['OU'])
                ->setUserPermissionLevel($rawDBResponse['User_Perm'])
                ->setGroupPermissionLevel($rawDBResponse["Group_Perm"])
                ->setPrivilegeID($rawDBResponse['Privilege_ID']);

        return $this;
    }

    public function updateInDatabase() {
        PermissionMapDatabase::modifyPermission($this);
    }

}
