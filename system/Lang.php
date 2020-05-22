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
 * Description of Lang
 *
 * @author cjacobsen
 */
abstract class Lang
{

    //put your code here

    public static function get($name)
    {
        $requestedLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        if (self::langExists($requestedLang)) {

            $target = '\app\lang\\' . $requestedLang . '\\' . strtoupper($requestedLang) . 'Common';
        } else {
            $target = '\app\lang\\' . DEFAULT_LANG . '\\' . strtoupper(DEFAULT_LANG) . 'Common';
        }
        //echo $target;
        app\AppLogger::get()->debug("Language translation: " . $name . ' -> ' . $target::get($name));
        return $target::get($name);
    }

    public static function getHelp($name)
    {
        $requestedLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        if (self::langExists($requestedLang)) {

            $target = '\app\lang\\' . $requestedLang . '\\' . strtoupper($requestedLang) . 'Common';
        } else {
            $target = '\app\lang\\' . DEFAULT_LANG . '\\' . strtoupper(DEFAULT_LANG) . 'Common';
        }
        //echo $target;
        return $target::getHelp($name);
    }

    private static function langExists($lang)
    {
        $path = APPPATH . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . strtoupper($lang) . 'Common.php';
        //app\AppLogger::get()->info("Language Path: ".$path);
        if (file_exists($path)) {

            return true;
        }
        app\AppLogger::get()->warning("Language reference for " . $lang . " was not found.");
        return false;
    }

}
