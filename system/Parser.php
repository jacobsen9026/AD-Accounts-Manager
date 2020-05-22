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
 * Description of Parser
 * A collection of functions to control app output buffering
 *
 * @author cjacobsen
 */

use System\App\AppLogger;

class Parser
{

    /**
     *
     * @param string $view
     *
     * @return boolean
     */
    public function view(string $view, array $params = null)
    {

        //var_dump($view);
        $view = $this->sanitize($view);

        $path = VIEWPATH . DIRECTORY_SEPARATOR . $view . ".php";
        //var_dump($path);
        if (file_exists($path)) {

            ob_start();
            try {
                AppLogger::get()->info("Rendering view file: " . $path);
                if (include $path) {

                    return ob_get_clean();
                } else {

                    AppLogger::get()->warning("Could not include view file: " . $path);
                }
            } catch (Exception $ex) {
                var_dump($ex);
            }

            ob_get_clean();
        } else {

            AppLogger::get()->warning("Could not find view file: " . $path);
        }
        return false;
    }

    /**
     *
     * @param stirng $modal
     *
     * @return boolean
     */
    public function modal(string $modal)
    {

        //var_dump($modal);
        $modal = $this->sanitize($modal);

        $path = VIEWPATH . DIRECTORY_SEPARATOR . "modals" . DIRECTORY_SEPARATOR . $modal . ".php";

        //var_dump($path);
        if (file_exists($path)) {
            // echo "test";
            //var_dump($path);
            ob_start();
            if (include $path) {
                return ob_get_clean();
            } else {
                AppLogger::get()->info("Rendering include file: " . $path);
                AppLogger::get()->warning("Could not include file: " . $path);
            }
            ob_get_clean();
        } else {

            AppLogger::get()->warning("Could not find include file: " . $path);
        }
        return false;
    }

    /**
     *
     * @param string $file
     *
     * @return boolean
     */
    public function include($file)
    {

        $file = $this->sanitize($file);

        $path = ROOTPATH . DIRECTORY_SEPARATOR . $file . ".php";
        //echo $path;
        if (file_exists($path)) {
            //ob_start();
            //echo "loaded";
            include $path;
            return true;
            //return ob_get_clean();
        } else {

            return false;
        }
    }

    /**
     *
     * @param string $path
     *
     * @return string
     */
    public function sanitize($path)
    {
        if ($path[0] == "/" or $path[0] == "\\") {
            $path = substr($path, 1);
        }
        $path = str_replace(['/', '\\'], strval(DIRECTORY_SEPARATOR), $path);
        return $path;
    }

    /**
     *
     * @param array $object
     *
     * @return string
     */
    public function varDump($object)
    {
        ob_start();
        var_dump($object);
        return ob_get_clean();
    }

    public static function get()
    {
        return new self();
    }

}
