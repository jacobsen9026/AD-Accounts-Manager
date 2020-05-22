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

namespace App\Models;

/**
 * Description of DatabaseInterface
 *
 * @author     cjacobsen
 * @deprecated since version number
 */

use app\database\Schema;
use System\Database;
use System\App\AppLogger;
use App\Models\Query;

abstract class DatabasePost
{
    //put your code here

    /**
     *
     * @param type $tableName
     * @param array $post
     */
    public static function setPost($primaryTable, $id, $post, $type = null)
    {
        $logger = AppLogger::get();
        $logger->debug('Setting database post');
        $logger->debug('Primary Table: ' . $primaryTable);
        $logger->debug('ID: ' . $id);
        $logger->debug($post);
        $logger->debug($_FILES);
        if (isset($_FILES) and !empty($_FILES)) {
            self::uploadFiles();
        }
        foreach ($post as $label => $value) {
            $value = self::preProcessValue($label, $value);
            $logger->debug($label);
            $logger->debug($value);
            $logger->debug(strpos($label, "_"));
            if (strpos($label, "_")) {
                //$logger->error('found _');
                $column = explode("_", $label, 2)[1];
                $table = explode("_", $label, 2)[0];
                $logger->debug($table);
                $logger->debug($column);
                if ($column == 'ID') {
                    $logger->info('Skipping ID column');
                    continue;
                }
                if ($table != $primaryTable) {
                    //The variable we are saving is different that the table provided to the method
                    // Therefore, the where should match against the given tables ID column
                    //var_dump($table . ' does not equal ' . $primaryTable);
                    //Prepare the Schema Constant Variable
                    //$schemaID = strtoupper($table) . '_' . strtoupper($primaryTable) . '_ID';
                    $schemaID = strtoupper($table) . '_ID';

                    $schemaClass = new \ReflectionClass('app\database\Schema');
                    $schema = $schemaClass->getConstant($schemaID);

                    $query = new Query($table, Query::UPDATE, $column);
                    $query->where($schema, $id)
                        ->set($column, $value);
                    if ($type != null) {
                        $query->where(Schema::ACTIVEDIRECTORY_TYPE[Schema::COLUMN], $type);
                    }
                    $query->run();
                    //  $query = 'UPDATE ' . $table . ' SET "' . $column . '" = "' . $value . '" WHERE ' . $schema[Schema::COLUMN] . ' = ' . $id;
                    // Database::get()->query($query);
                } else {

                    $query = 'UPDATE ' . $table . ' SET "' . $column . '" = "' . $value . '" WHERE ID = ' . $id;
                    Database::get()->query($query);
                }
            }
        }
    }

    private static function preProcessValue($label, $value)
    {
        //var_dump($label);
        switch ($label) {
            case Schema::DISTRICT_AD_NETBIOS[Schema::NAME]:
                return strtoupper($value);

                break;

            case Schema::APP_ADMIN_PASSWORD[Schema::NAME]:

                if (strlen($value) != 64) {
                    return hash("sha256", $value);
                } elseif (strlen($value) == 0) {
                    return AppDatabase::getAdminPassword();
                }
                return $value;
            case 'oauth':

                self::uploadOAuth();
                return null;

            default:
                return $value;
                break;
        }
    }

    private static function uploadFiles()
    {
        //var_dump($_FILES);
        //exit;
        foreach ($_FILES as $key => $file) {
            if ($key == 'client_secret') {
                move_uploaded_file($file['tmp_name'], GAMPATH . DIRECTORY_SEPARATOR . 'client_secret.json');
            }
        }
        //exit;
    }

}
