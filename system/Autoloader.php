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

namespace system;

/**
 * Description of Autoloader
 *
 * @author cjacobsen
 */
abstract class Autoloader {

    public static function run(Core $core) {

        include(ROOTPATH . DIRECTORY_SEPARATOR . "system" . DIRECTORY_SEPARATOR . "CoreFunctions.php");
        spl_autoload_register(function ($class) {
            //var_dump($class);
            // Check root namspace PSR path
            $filename = ROOTPATH . DIRECTORY_SEPARATOR . $class . '.php';
            if (!class_exists($class)) {
                if (file_exists($filename)) {
                    try {
                        require $filename;
                    } catch (Exception $ex) {
                        echo $ex;
                    }
                } else {
                    // Check app lib directory PSR path
                    $filename = ROOTPATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $class . '.php';
                    if (file_exists($filename)) {
                        try {
                            require $filename;
                        } catch (Exception $ex) {
                            echo $ex;
                        }
                    } else {
                        // Check system lib directory PSR path
                        $filename = ROOTPATH . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $class . '.php';
                        if (file_exists($filename)) {
                            try {
                                require $filename;
                            } catch (Exception $ex) {
                                echo $ex;
                            }
                        }
                    }
                }
            }
        });
    }

}

?>