<?php

/*
 * The MIT License
 *
 * Copyright 2019 cjacobsen.
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

namespace app\config;

/**
 * Description of AppConfig
 *
 * @author cjacobsen
 */
use system\common\CoreConfig;

class AppConfig extends CoreConfig {

    //put your code here

    protected $name = "School Accounts Manager";
    protected $forceHTTPS = false;
    protected $timeout = 1200;
    protected $admins = null;
    protected $motd = null;

    function __construct(array $keyValuePairs = null) {
        parent::__construct($keyValuePairs);
    }

    public function getName() {
        return $this->name;
    }

    public function getForceHTTPS() {
        return $this->forceHTTPS;
    }

    public function getTimeout() {
        return $this->timeout;
    }

    public function getAdmins() {
        return $this->admins;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setForceHTTPS($forceHTTPS) {
        $this->forceHTTPS = $forceHTTPS;
    }

    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    public function setAdmins($admins) {
        $admins = trim($admins);
        if (empty($this->admins)) {
            $this->admins[] = $admins;
        } else {
            var_dump($admins);
            $this->admins = explode("\n", $admins);
        }
    }

}
