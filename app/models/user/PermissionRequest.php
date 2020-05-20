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
 * Description of PermissionRequest
 *
 * @author cjacobsen
 */
use App\Models\User\User;
use App\Models\User\PermissionLevel;

class PermissionRequest {

    private $user;
    private $ou;
    private $permissionType;
    private $permissionLevel;

    /**
     * Creates a new PermissionRequest for use in the PermissionHandler
     * @param User $user
     * @param type $ou The OU we want to validate access to
     * @param type $permissionType Options are {PermissionType::STUDENT_USER,PermissionType::STUDENT_GROUP,PermissionType::STAFF_USER,PermissionType::STAFF_GROUP}
     * @param type $permissionLevel Options are int(0-4) Meaning differs based on permission type, but generally (Read,Change,Write,Delete)
     */
    public function __construct(User $user, $ou, PermissionLevel $permissionLevel) {
        $this->user = $user;
        $this->ou = $ou;
        $this->permissionLevel = $permissionLevel;
    }

    public function getPermissionType() {
        return $this->permissionType;
    }

    public function getPermissionLevel() {
        return $this->permissionLevel;
    }

    public function setPermissionType($permissionType) {
        $this->permissionType = $permissionType;
        return $this;
    }

    public function setPermissionLevel($permissionLevel) {
        $this->permissionLevel = $permissionLevel;
        return $this;
    }

    public function getUser() {
        return $this->user;
    }

    public function getOu() {
        return $this->ou;
    }

    public function setUser($user) {
        $this->user = $user;
        return $this;
    }

    public function setOu($ou) {
        $this->ou = $ou;
        return $this;
    }

    //put your code here
}
