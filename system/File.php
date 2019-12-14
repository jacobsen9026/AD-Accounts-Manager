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

    public static function refreshSchemaDefinitions($constantsTable) {
        $writeFooter = false;
        if (!file_exists(self::SCHEMA_FILE_PATH)) {
            touch(self::SCHEMA_FILE_PATH);
            $output = "<?php \n"
                    . " namespace app\database; \n"
                    . " class Schema { \n"
                    . "\n Pop This"
                    . "\n Pop This";
            file_put_contents(self::SCHEMA_FILE_PATH, $output, FILE_APPEND);
            $writeFooter = true;
        }
        $contents = file(File::SCHEMA_FILE_PATH);
        array_pop($contents);
        array_pop($contents);
        file_put_contents(File::SCHEMA_FILE_PATH, $contents);

        foreach ($constantsTable as $title => $value) {
            //var_dump($title);

            $output = "    const " . strtoupper($title) . " = '" . $value . "';\n";
            if (!in_array($output, $contents)) {
                file_put_contents(self::SCHEMA_FILE_PATH, $output, FILE_APPEND);
            }
        }
        file_put_contents(self::SCHEMA_FILE_PATH, "\n }", FILE_APPEND);
        //$this->redirect('/settings');
    }

}

?>