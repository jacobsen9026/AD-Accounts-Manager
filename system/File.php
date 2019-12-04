<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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

}

?>