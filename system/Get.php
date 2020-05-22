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

namespace System;

/**
 * Description of Get
 *
 * Represents the Get Variable of the Request
 *
 * @author cjacobsen
 */
abstract class Get
{

    /**
     * Check if the GET was used in the request
     *
     * @return boolean
     */
    public static function isSet()
    {
        if (isset($_GET) and $_GET != null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the contents of the GET array, or false if GET was not used.
     *
     * @return null|array
     */
    public static function getAll()
    {
        if (Get::isSet()) {
            return $_GET;
        } else {
            return null;
        }
    }

    public static function get($key)
    {
        if (self::isSet($_GET[$key])) {
            app\AppLogger::get()->info("Getting Get variable " . $key . ' ' . $_GET[$key]);
            return $_GET[$key];
        } else {
            return null;
        }
    }

}
