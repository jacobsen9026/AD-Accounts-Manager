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

namespace system\app;

/**
 * Description of Session
 *
 * @author cjacobsen
 */
use app\models\user\User;
use app\models\database\AuthDatabase;

abstract class Session {
    //put your code here

    /** @var User|null */
    const TIMEOUT = 'timeout';
    const USER = 'user';

    public static function getUser() {
        if (isset($_SESSION[self::USER]) and $_SESSION[self::USER] != '') {
            if (Session::getTimeoutStatus()) {
                return new User();
            } else {
                return unserialize($_SESSION[self::USER]);
            }
        }
        return new User();
    }

    public static function getID() {
        return session_id();
    }

    public static function setUser($user) {
        if ($_SESSION[self::USER] = serialize($user)) {
            return true;
        }
        return false;
    }

    /**
     * Update the user timeout with the value set in the application settings
     */
    public static function updateTimeout() {

        $nextTimeout = AuthDatabase::getSessionTimeout() + time();
        $_SESSION[self::TIMEOUT] = $nextTimeout;
    }

    public static function getTimeoutStatus() {
        if (time() > $_SESSION[self::TIMEOUT]) {
            return true;
        }
        return false;
    }

    public static function end() {
        session_unset();
        Cookie::delete("PHPSESSID");
    }

}
