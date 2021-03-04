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

use App\Models\Audit\Action\Settings\RemovePermissionAuditAction;
use App\Models\Database\PrivilegeLevelDatabase;
use App\Models\Model;
use App\Models\Database\PermissionMapDatabase;

class Permission extends Model
{

    private $id;
    private $refID;
    private $ou;
    private $privilegeID;
    private $userPermissionLevel;
    private $groupPermissionLevel;

    public function getRefID()
    {
        return $this->privilegeID . hash("sha256", $this->getOu());
    }

    public function getOu()
    {
        return $this->ou;
    }

    public function setOu($ou)
    {
        $this->ou = $ou;
        $this->ouDepth = count(explode('OU=', $ou));
        return $this;
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

    public function getGroupName()
    {
        $privilege = new PrivilegeLevel();
        $privilege->importFromDatabase(PrivilegeLevelDatabase::get($this->getPrivilegeID()));
        return $privilege->getAdGroup();
    }

    public function getPrivilegeID()
    {
        return $this->privilegeID;
    }

    public function setPrivilegeID($privilegeID)
    {
        $this->privilegeID = $privilegeID;
        return $this;
    }


    public function getType()
    {
        return $this->type;
    }

    public function getUserPermissionLevel()
    {
        return $this->userPermissionLevel;
    }

    public function setUserPermissionLevel($userPermissionLevel)
    {
        $this->logger->info("Setting user permission level to " . $userPermissionLevel);
        $this->userPermissionLevel = $userPermissionLevel;
        return $this;
    }

    public function getGroupPermissionLevel()
    {
        return $this->groupPermissionLevel;
    }

    public function setGroupPermissionLevel($groupPermissionLevel)
    {
        $this->groupPermissionLevel = $groupPermissionLevel;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getOuDepth()
    {
        return $this->ouDepth;
    }

    public function setOuDepth($ouDepth)
    {
        $this->ouDepth = $ouDepth;
        return $this;
    }

    public function loadFromDBByID(int $id)
    {
        $this->loadFromDatabaseResponse(PermissionMapDatabase::getPermissionByID($id));
    }

    public function loadFromDatabaseResponse($rawDBResponse)
    {
        //var_dump($rawDBResponse);

        $this->setId($rawDBResponse['ID'])
            ->setOu($rawDBResponse['OU'])
            ->setUserPermissionLevel($rawDBResponse['User_Perm'])
            ->setGroupPermissionLevel($rawDBResponse["Group_Perm"])
            ->setPrivilegeID($rawDBResponse['Privilege_ID']);

        return $this;
    }

    public function updateInDatabase()
    {
        PermissionMapDatabase::modifyPermission($this);
    }

    public function remove()
    {
        $this->audit(new RemovePermissionAuditAction($this));
        return PermissionMapDatabase::removePermissionByID($this->id);

    }
}
