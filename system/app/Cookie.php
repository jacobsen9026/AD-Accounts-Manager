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

namespace System\App;

/**
 * Description of Cookie
 *
 * @author nbranco
 */
abstract class Cookie {

    /**
     *
     * @param type $cookieName The Name of the cookie
     * @return boolean | mixed Returns false if cookie does not exist
     */
    public static function get($name) {

        if (!is_null($_COOKIE)) {
            if (key_exists($name, $_COOKIE)) {
                return $_COOKIE[$name];
            }
        }
        return false;
    }

    /**
     *
     * @param type $name  The Name of the Cookie
     * @param type $value  The Value of the Cookie
     * @param type $expires  The length of time until the expires, measured in seconds.  If not used, the cookie will be set to expire in 100 years
     */
    public static function set($name, $value = "", $expires = null, $path = "/") {

        if (is_null($expires)) {
            $expires = 100 * 365 * 24 * 60 * 60;
        }
        setcookie($name, $value, time() + $expires, $path);
    }

    public static function delete($name) {
        setcookie($name, "", time() - 3600);
    }

}
