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

namespace System\Traits;

/**
 *
 * @author cjacobsen
 */

use System\SystemLogger;

trait Parser
{

    public function startBuffer()
    {

        ini_set('output_buffering', 0);
        ob_end_clean();
        header('Transfer-Encoding', 'chunked');
        header('Content-Type', 'text/html');
        header('Connection', 'keep-alive');
        //header('Connection', 'close');
        ob_flush();
        flush();

        $p = "";  //padding
        echo "<script>";
        for ($i = 0; $i < 4 * 1024; $i++) {
            echo "var a;\r\n";
        };
        echo "</script>";

        ob_flush();
        flush();
    }

    /**
     *
     * @param string $view
     *
     * @return boolean
     */
    public function view($view, array $params = null)
    {
        $logger = SystemLogger::get();
        if (!is_null($params)) {
            $logger->debug($params);
        }
        //var_dump($view);
        $view = self::sanitize($view);

        $path = VIEWPATH . DIRECTORY_SEPARATOR . $view . ".php";
        //var_dump($path);
        if (file_exists($path)) {

            ob_start();
            try {
                $logger->info("Rendering view file: " . $path);
                if (include($path)) {
                    return ob_get_clean();
                } else {
                    ob_clean();
                    $logger->warning("Could not include view file: " . $path);
                }
            } catch (Exception $ex) {
                ob_clean();
                $logger->warning($ex);
            }

            ob_clean();
        } else {

            $logger->warning("Could not find view file: " . $path);
        }
        return false;
    }

    /**
     *
     * @param string $path
     *
     * @return string
     */
    public static function sanitize($path)
    {
        if ($path[0] == "/" or $path[0] == "\\") {
            $path = substr($path, 1);
        }
        $path = str_replace(['/', '\\'], (string)DIRECTORY_SEPARATOR, $path);
        return $path;
    }

    public function sendBuffer($content)
    {

        echo $content;
        //var_dump(ob_get_length());
        ob_flush();
        flush();
    }

    /**
     *
     * @param stirng $modal
     *
     * @return boolean
     */
    public function modal($modal)
    {

        $modal = $this->sanitize($modal);

        $path = VIEWPATH . DIRECTORY_SEPARATOR . "modals" . DIRECTORY_SEPARATOR . $modal . ".php";
        if (file_exists($path)) {
            ob_start();
            if (include($path)) {
                return ob_get_clean();
            } else {
                SystemLogger::get()->info("Rendering include file: " . $path);
                SystemLogger::get()->warning("Could not include file: " . $path);
            }
            ob_clean();
        } else {

            SystemLogger::get()->warning("Could not find include file: " . $path);
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
        if (file_exists($path)) {
            include $path;
            return true;
        } else {

            return false;
        }
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

}
