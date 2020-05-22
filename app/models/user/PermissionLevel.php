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
 * Description of PermissionType
 *
 * @author cjacobsen
 */
class PermissionLevel
{

    const USERS = "User_Perm";
    const USER_READ = 1;
    const USER_CHANGE = 2;
    const USER_UNLOCK = 3;
    const USER_DISABLE = 4;
    const GROUPS = "Group_Perm";
    const GROUP_READ = 1;
    const GROUP_CHANGE = 2;
    const GROUP_ADD = 3;
    const GROUP_DELETE = 4;

    private $name;
    private $id;

    public function __construct($name = null, $id = null)
    {
        $this->setDisplayName($name)
            ->setId($id);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setDisplayName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @return PermissionLevel[]
     */
    public static function getGroupTypes()
    {
        return [
            new PermissionLevel("0-None", 0),
            new PermissionLevel("1-Read", 1),
            new PermissionLevel("2-Change", 2),
            new PermissionLevel("3-Add", 3),
            new PermissionLevel("4-Delete", 4)
        ];
    }

    /**
     *
     * @return PermissionLevel[]
     */
    public static function getUserTypes()
    {
        return [
            new PermissionLevel("0-None", 0),
            new PermissionLevel("1-Read", 1),
            new PermissionLevel("2-Change", 2),
            new PermissionLevel("3-Unlock", 3),
            new PermissionLevel("4-Disable", 4)
        ];
    }

}
