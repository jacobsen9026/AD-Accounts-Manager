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
 * Description of PrivilegeLevel
 *
 * @author cjacobsen
 */

use App\Models\Database\PermissionMapDatabase;
use App\Models\Database\PrivilegeLevelDatabase;

class PrivilegeLevel
{

    private $id;
    private $adGroup;
    private $superAdmin;

    public function importFromDatabase($data)
    {
        $this->setId($data['ID'])
            ->setAdGroup($data['AD_Group_Name'])
            ->setSuperAdmin($data['Super_Admin']);
        return $this;
    }

    public function saveToDatabase()
    {
        PrivilegeLevelDatabase::updatePrivilegeLevel($this->getId(), $this->getAdGroup(), $this->getSuperAdmin());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    public function getAdGroup()
    {
        return $this->adGroup;
    }

    public function setAdGroup($adGroup)
    {
        $this->adGroup = $adGroup;
        return $this;
    }

    public function getSuperAdmin()
    {
        return $this->superAdmin;
    }

    public function setSuperAdmin($superAdmin)
    {
        $this->superAdmin = $superAdmin;
        return $this;
    }


}
