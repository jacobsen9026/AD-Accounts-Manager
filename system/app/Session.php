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
use system\common\CoreSession;
use app\models\user\User;
use app\config\MasterConfig;

abstract class Session extends CoreSession {
    //put your code here

    /** @var User|null */
    const TIMEOUT = 'timeout';

    /**
     *
     * @return Session
     */
    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function getUser() {
        if (isset($_SESSION['user']) and $_SESSION['user'] != '') {
            if (Session::getTimeoutStatus()) {
                session_destroy();
                return new User();
            } else {
                return unserialize($_SESSION['user']);
            }
        }
        return new User();
    }

    public static function setUser($user) {
        var_dump($user);
        if ($_SESSION['user'] = serialize($user)) {
            return true;
        }
        return false;
    }

    public static function updateTimeout() {
        /* @var $config MasterConfig */
        $config = MasterConfig::get();
        $nextTimeout = $config->app->getTimeout() + time();
        //$nextTimeout = 1;
        $_SESSION['timeout'] = $nextTimeout;
    }

    public static function getTimeoutStatus() {
        if (time() > $_SESSION['timeout']) {
            return true;
        }
        return false;
    }

}
