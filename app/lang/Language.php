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

namespace App\Lang;

/**
 * Description of Language
 *
 * @author cjacobsen
 */
trait Language
{

    //put your code here

    public static function get($name)
    {
        if (isset(self::$strings[$name]) and self::$strings[$name] != null) {
            return self::$strings[$name];
        }
        return 'No language reference found for ' . $name;
    }

    public static function getHelp($name)
    {
        if (isset(self::$help[$name]) and self::$help[$name] != null) {
            return self::$help[$name];
        }
        return 'No help language reference found for ' . $name;
    }

    public static function getError($name)
    {
        if (isset(self::$error[$name]) and self::$error[$name] != null) {
            return self::$error[$name];
        }
        return 'No error language reference found for ' . $name;
    }

}
