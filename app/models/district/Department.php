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

namespace app\models\district;

/**
 * Description of School
 *
 * @author cjacobsen
 */
use app\database\Schema;
use app\models\Query;

class Department {

    const TABLE_NAME = 'Department';

    //put your code here
    public $name;

    /** @var Grade The grades contained within this school */
    public $grades;

    function __construct($name) {
        $this->name = $name;
    }

    public static function getDepartments($schoolID) {
        $query = new Query(self::TABLE_NAME);
        //var_dump($schoolID);
        $query->where(Schema::DEPARTMENT_SCHOOL_ID, $schoolID);
        $result = 'no result';
        $result = $query->run();
        //var_dump($result);
        return $result;
    }

    public static function createDepartment($name, $schoolID) {
        \system\app\AppLogger::get()->debug("Creating new department named: " . $name);
        return \system\Database::get()->query('INSERT INTO ' . self::TABLE_NAME . ' (' . Schema::DEPARTMENT_NAME[Schema::COLUMN] . ',' . Schema::DEPARTMENT_SCHOOL_ID[Schema::COLUMN] . ') VALUES ("' . $name . '", "' . $schoolID . '")');
    }

    public static function getSchoolID($departmentID) {
        $query = new Query(self::TABLE_NAME, Query::SELECT, Schema::DEPARTMENT_SCHOOL_ID[Schema::COLUMN]);
        $query->where(Schema::DEPARTMENT_ID, $departmentID);
        return $query->run();
    }

    public static function getDepartment($departmentID) {
        $query = new Query(self::TABLE_NAME);
        $query->where(Schema::DEPARTMENT_ID, $departmentID);

        return $query->run()[0];
    }

    public static function deleteSchool($departmentID) {
        \system\app\AppLogger::get()->debug("Delete department id: " . $departmentID);
        return \system\Database::get()->query('DELETE FROM ' . self::TABLE_NAME . ' WHERE ' . Schema::DEPARTMENT_ID[Schema::COLUMN] . '=' . $departmentID);
    }

    public static function getADSettings($departmentID, $type) {
        $query = new Query(Schema::ACTIVEDIRECTORY);
        $query->where(Schema::ACTIVEDIRECTORY_DEPARTMENT_ID, $departmentID)
                ->where(Schema::ACTIVEDIRECTORY_TYPE, $type);

        return $query->run()[0];
    }

    public static function getGASettings($departmentID, $type) {
        $query = new Query(Schema::GOOGLEAPPS);
        $query->where(Schema::GOOGLEAPPS_DEPARTMENT_ID, $departmentID)
                ->where(Schema::GOOGLEAPPS_TYPE, $type);
        return $query->run()[0];
    }

}
