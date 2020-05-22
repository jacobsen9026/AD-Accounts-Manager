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

namespace System;

/**
 * Description of Autoloader
 * This class handles autoloading of all application, system, and vendor classes
 * There are a number of hardcoded directories that are searched through to find a class by name
 * app/lib system/lib and the root path
 *
 * Any classes inside of these are referenced from the path.
 * EG: a class Car in system/lib is referenced by \Car
 * EG: a class Car in system is referenced by \system\Car
 *
 * Due to the abstract and postloaded nature of this class, logging is unavailable.
 *
 * @author cjacobsen
 */
abstract class Autoloader
{

    public static function run()
    {
        /*
         * Load Composers Autoloader to include Composer packages
         */
        require_once ROOTPATH . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
        /*
         * Load global core functions that will be available to the entire Core
         */
        include(ROOTPATH . DIRECTORY_SEPARATOR . "system" . DIRECTORY_SEPARATOR . "CoreFunctions.php");
        /*
         * Register the class autoloader, all future class loads will be performed with the following function
         */
        spl_autoload_register(function ($class) {
            // Check root namspace PSR path
            $filename = ROOTPATH . DIRECTORY_SEPARATOR . $class . '.php';
            if (!class_exists($class)) {
                if (file_exists($filename)) {
                    try {
                        /*
                         * Classfile was found, attempt to include it
                         */
                        require $filename;
                    } catch (Exception $ex) {
                        /*
                         * Classfile inclusion had an error so print the error
                         */
                        echo $ex;
                    }
                } else {
                    /*
                     * File wasn't found in root PSR path so now we check the App Lib directory
                     */
                    $filename = ROOTPATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $class . '.php';
                    if (file_exists($filename)) {
                        try {
                            require $filename;
                        } catch (Exception $ex) {
                            echo $ex;
                        }
                    } else {
                        /*
                         * File was also not found in App Lib so one final check for the class in the System Lib folder
                         */
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