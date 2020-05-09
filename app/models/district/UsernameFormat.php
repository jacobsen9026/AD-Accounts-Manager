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

namespace app\models\district;

/**
 * Description of UsernameFormat
 *
 * @author cjacobsen
 * @deprecated
 * @
 */
abstract class UsernameFormat {

    //put your code here
    const FILN = array('{First Initial}{Last Name}', 'uf_filn');
    const YOGFILN = array('{Year of Grad}{First Initial}{Last Name}', 'uf_yogfiln');

    public static function getUsernameFormats() {
        return array(self::FILN, self::YOGFILN);
    }

    public static function formatUsername($firstName, $lastName, $format, $yog = null, $middleName = null) {
        switch ($format) {
            case self::FILN:

                return substr($firstName, 0, 1) . $lastName;
                break;
            case self::YOGFILN:

                return $yog . substr($firstName, 0, 1) . $lastName;
                break;
            default:
                break;
        }
    }

}
