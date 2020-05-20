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

namespace System\App\Forms\Validators;

/**
 * Description of GroupValidator
 *
 * @author cjacobsen
 */
use App\Models\District\Group;

class GroupValidator extends FormInputValidator {

    const ADD_GROUP = "addGroup";

    /**
     *
     * @var Group
     */
    private $group;

    public function __construct() {
        $this->group = new Group();
    }

    public function addGroup() {
        var_dump($this->group);
        return
        foreach ($input as $field => $value) {
            var_dump($field);
            var_dump($value);
        }
    }

    public function setName($name) {
        $this->group->setName($name);
        return $this;
    }

    public function setDescription($description) {
        $this->group->setDescription($description);
        return $this;
    }

    public function setEmail($email) {
        $this->group->setEmail($email);
        return $this;
    }

    public function setOu($ou) {
        $this->ou = $ou;
        return $this;
    }

}
