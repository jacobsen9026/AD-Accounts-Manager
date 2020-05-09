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

if (!function_exists('enablePHPErrors')) {

    function enablePHPErrors() {
        /*
          $bt = debug_backtrace(1);
          $caller = array_shift($bt);
          echo $caller['file'] . ":" . $caller["line"]; */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
    }

}
if (!function_exists('disablePHPErrors')) {

    function disablePHPErrors() {

        error_reporting(0);
        ini_set('display_errors', FALSE);
        ini_set('display_startup_errors', FALSE);
    }

}
if (!function_exists('backTrace')) {

    function backTrace($startOffset = null) {
        $bt = debug_backtrace(1);
        //var_dump($bt);
        $caller = null;
        if ($startOffset == null) {
            $caller = $bt[3];
        } elseif (array_key_exists($startOffset, $bt)) {


            $caller = $bt[$startOffset];
        }

        //var_dump($caller);
        //$caller = array_shift($caller);
        return $caller;
    }

}
if (!function_exists("array_flatten")) {

    function array_flatten($array) {
        if (is_array($array)) {
            $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
            foreach ($it as $v) {
                $flat[] = $v;
            }
            return $flat;
        } else {
            return $array;
        }
    }

}
?>
