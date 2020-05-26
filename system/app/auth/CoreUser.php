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

namespace System\App\Auth;

/**
 * Description of CoreUser
 *
 * @author cjacobsen
 */

use System\App\UserLogger;

class CoreUser
{

    const ADMINISTRATOR = "admin";

    //put your code here
    public $username;
    public $privilege;

    /**
     *
     * @var bool
     */
    public $authenticated = false;
    public $apiToken;

    public function __construct()
    {

    }

    /**
     *
     * @return type
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param bool $status
     *
     * @return $this
     */
    public function authenticated($status = true)
    {
        UserLogger::get()->info('Setting Authenticated to: ' . $status);
        $this->authenticated = $status;
        return $this;
    }


    /**
     *
     * @param type $username
     *
     * @return $this
     */
    public function setUsername(string $username)
    {

        UserLogger::get()->info('Setting Username to: ' . $username);
        $this->username = $username;
        return $this;
    }


    /**
     *
     */
    public function generateAPIToken()
    {
        $user = gzcompress($this);
        $this->apiToken = \system\Encryption::encrypt(serialize($user));
    }

    public function getApiToken()
    {
        return $this->apiToken;
    }

}
