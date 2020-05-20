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
 * Description of File
 *
 * @author cjacobsen
 */
abstract class File {
    //put your code here

    /**
     * getAllFilesInDirectory
     *
     * @param Core $app
     * @return File $files
     */
    const SCHEMA_FILE_PATH = \APPPATH . \DIRECTORY_SEPARATOR . "database" . \DIRECTORY_SEPARATOR . "Schema.php";

    public static function getFiles($dir) {
        $files = null;
        //echo 'Dir';
        //var_dump($dir);
        foreach (scandir($dir) as $file) {
            //echo $dir.DIRECTORY_SEPARATOR.$file;
            if ($file != "." and $file != ".." and is_file($dir . DIRECTORY_SEPARATOR . $file)) {
                //echo 'file';
                //var_dump($file);


                $files[] = realpath($dir . DIRECTORY_SEPARATOR . $file);
            }
        }
        return $files;
    }

    public static function getFolders($dir) {
        $folders = null;
        $folderPath = scandir($dir . DIRECTORY_SEPARATOR);
        $folders = Array();
        foreach ($folderPath as $folder) {
            if ($folder != "." and $folder != ".." and is_dir($folder) and $folder[0] != ".") {
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
     * @return array A list of file names
     */
    public static function getAllFiles($dir) {
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        $files[] = null;
        //echo 'Dir';
        //var_dump($dir);
        foreach ($rii as $file) {

            if (basename($file) == "." or basename($file) == ".." or is_dir($file)) {
                continue;
            }

            $files[] = realpath($file);
        }
        //var_dump($files);
        return $files;
    }

    /**
     * Writes to app/database/schema to update the column constants
     * Only used during development
     * @param type $constantsTable
     */
    public static function refreshSchemaDefinitions($constantsTable) {
        $writeFooter = false;
        if (!file_exists(self::SCHEMA_FILE_PATH)) {
            touch(self::SCHEMA_FILE_PATH);
            $output = "<?php \n"
                    . " namespace app\database; \n"
                    . " class Schema { \n"
                    . "\n"
                    . "    const NAME = 'name';\n"
                    . "    const TABLE = 'table';\n"
                    . "    const COLUMN = 'column';\n";
            foreach (Database::get()->getAllTables() as $table) {
                $output .= "    const " . strtoupper($table) . " = '$table';\n";
            }
            $output .= "Pop This"
                    . "\n Pop This";
            file_put_contents(self::SCHEMA_FILE_PATH, $output, FILE_APPEND);
            $writeFooter = true;
        }
        $contents = file(File::SCHEMA_FILE_PATH);
        array_pop($contents);
        array_pop($contents);
        file_put_contents(File::SCHEMA_FILE_PATH, $contents);
        // $output = file_put_contents(self::SCHEMA_FILE_PATH, $output, FILE_APPEND);
        foreach ($constantsTable as $title => $value) {
            //var_dump($title);

            $output = "    const " . strtoupper($title) . " = ";
            $output .= "array('table'=>'" . explode("_", $title)[0] . "','column'=>'" . $value . "','name'=>'" . $title . "');\n";

            if (!in_array($output, $contents)) {
                file_put_contents(self::SCHEMA_FILE_PATH, $output, FILE_APPEND);
            }
        }
        file_put_contents(self::SCHEMA_FILE_PATH, "\n }", FILE_APPEND);
        //$this->redirect('/settings');
    }

    public static function overwriteFile($filepath, $contents) {
        file_put_contents($filepath, $contents);
    }

    public static function appendToFile($filepath, $contents) {
        file_put_contents($filepath, $contents, FILE_APPEND);
    }

    public static function deleteFile($filepath) {
        unlink($filepath);
    }

    public static function getContents($filepath) {
        return file_get_contents($filepath);
    }

}

?>