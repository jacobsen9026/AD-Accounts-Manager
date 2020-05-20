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
 * Description of School
 *
 * @author cjacobsen
 */
use App\Models\Model;

class School extends Model {

    private $name;
    private $abbr;
    private $ou;
    private $id;

    public function importFromAD($LDAPResponse) {
        \System\App\AppLogger::get()->debug($LDAPResponse);

        if (key_exists("displayname", $LDAPResponse))
            $this->setName($LDAPResponse["displayname"][0]);
        $this->setAbbr($LDAPResponse["ou"][0]);
        $this->setOU($LDAPResponse["distinguishedname"][0]);
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function getAbbr() {
        return $this->abbr;
    }

    public function getOu() {
        return $this->ou;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setAbbr($abbr) {
        $this->abbr = $abbr;
        return $this;
    }

    public function setOu($ou) {
        $this->ou = $ou;
        return $this;
    }

}
