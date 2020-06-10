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


use FilesystemIterator;
use PHPMailer\PHPMailer\Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Description of File
 *
 * @author cjacobsen
 */
abstract class File
{
    //put your code here

    /**
     * getAllFilesInDirectory
     *
     * @param Core $app
     *
     * @return File $files
     */
    const SCHEMA_FILE_PATH = APPPATH . DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . "Schema.php";

    public static function getFiles($dir)
    {
        $files = null;
        foreach (scandir($dir) as $file) {
            if ($file !== "." and $file !== ".." and is_file($dir . DIRECTORY_SEPARATOR . $file)) {
                $files[] = realpath($dir . DIRECTORY_SEPARATOR . $file);
            }
        }
        return $files;
    }

    public static function getFolders($dir)
    {
        $folders = null;
        $folderPath = scandir($dir . DIRECTORY_SEPARATOR);
        $folders = [];
        foreach ($folderPath as $folder) {
            if ($folder !== "." and $folder !== ".." and is_dir($folder) and $folder[0] !== ".") {
                $folders[] = realpath($dir . DIRECTORY_SEPARATOR . $folder);
                //echo "Scaned folder " . $folder . "<br/>";
            }
        }
        return $folders;
    }

    /**
     * Returns a list of filenames in a given directory
     *
     * @param string $dir
     *
     * @return array A list of file names
     */
    public static function getAllFiles($dir)
    {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        $files[] = null;
        foreach ($rii as $file) {

            if (basename($file) === "." or basename($file) === ".." or is_dir($file)) {
                continue;
            }

            $files[] = realpath($file);
        }
        return $files;
    }


    public static function overwriteFile($filepath, $contents)
    {
        return file_put_contents($filepath, $contents);
    }

    public static function appendToFile($filepath, $contents)
    {
        $fileDir = substr($filepath, 0, strrpos($filepath, DIRECTORY_SEPARATOR));

        if (!file_exists($filepath)) {
            var_dump($fileDir);
            self::createDirectory($fileDir);

            touch($filepath);
        }
        file_put_contents($filepath, $contents, FILE_APPEND);

    }

    public
    static function createDirectory(string $dir)
    {
        $parentDir = substr($dir, 0, strrpos($dir, DIRECTORY_SEPARATOR));
        var_dump($parentDir);
        if (!file_exists($parentDir)) {
            self::createDirectory($parentDir);
        }
        var_dump("Parent exists");
        if (!file_exists($dir)) {
            var_dump("Making $dir");
            if (!mkdir($dir)) {
                if (!is_dir($dir)) {
                    return false;

                }
            }
        }

        return true;

    }

    public
    static function deleteFile($filepath)
    {
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return true;
    }

    public
    static function getContents($filepath)
    {
        SystemLogger::get()->debug($filepath);
        if (file_exists($filepath)) {
            return file_get_contents($filepath);
        } else {
            SystemLogger::get()->warning('File not found: ' . $filepath);
            //throw new FileException("File not found.", FileException::FILE_NOT_FOUND);
        }
    }

    public
    static function getMaximumUploadSize()
    {
        return ini_get('upload_max_filesize');
    }

    public
    static function exists(string $liveFile)
    {
        return file_exists($liveFile);
    }

    public
    static function removeDirectory(string $dir)
    {

        foreach (scandir($dir) as $file) {
            if ('.' === $file || '..' === $file) continue;
            if (is_dir("$dir/$file")) self::removeDirectory("$dir/$file");
            else unlink("$dir/$file");
        }
        rmdir($dir);
        return true;
    }

    public
    static function dirEmpty(string $dir)
    {
        $iterator = new FilesystemIterator($dir);
        return !$iterator->valid();
    }

    /**
     * @param $dir
     * @param bool $recursive
     *
     * @return int
     */
    public
    static function fileCount($dir, $recursive = true): int
    {


        $count = 0;


        $dir = new RecursiveDirectoryIterator ($dir);
        /**
         * @var \SplFileInfo $file
         */
        foreach (new RecursiveIteratorIterator($dir) as $file) {
            if ($file->getBasename() !== '.' && $file->getBasename() !== '..') {

                $count++;
            }

        }

        return $count;

    }

    public
    static function getModTime($filepath)
    {
        if (file_exists($filepath)) {

            return filemtime($filepath);
        }
        return 0;
    }

    public
    static function getSize($filepath)
    {

        if (file_exists($filepath)) {
            return filesize($filepath);
        }
        return 0;
    }

    public
    static function getMD5($sourceFile)
    {
        if (file_exists($sourceFile)) {
            return md5_file($sourceFile);
        }
        return null;
    }


}

?>