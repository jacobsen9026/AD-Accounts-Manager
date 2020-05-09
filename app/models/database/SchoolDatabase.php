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

namespace app\models\database;

/**
 * Description of School
 *
 * @author cjacobsen
 */
use app\database\Schema;
use app\models\Query;

class SchoolDatabase {

    const TABLE_NAME = 'School';

    //put your code here
    public $name;

    /** @var Grade The grades contained within this school */
    public $grades;

    function __construct($name) {
        $this->name = $name;
    }

    public static function createSchool($name, $abbr, $ou, $districtID) {
        \system\app\AppLogger::get()->debug("Creating new district named: " . $name);
        return \system\Database::get()->query('INSERT INTO ' . self::TABLE_NAME . ' (' . Schema::SCHOOL_NAME[Schema::COLUMN] . ',' . Schema::SCHOOL_ABBREVIATION[Schema::COLUMN] . ',' . Schema::SCHOOL_OU[Schema::COLUMN] . ',' . Schema::SCHOOL_DISTRICT_ID[Schema::COLUMN] . ') VALUES ("' . $name . '","' . $abbr . '","' . $ou . '","' . $districtID . '")');
    }

    public static function getDistrictID($schoolID) {
        $query = new Query(self::TABLE_NAME, Query::SELECT, Schema::SCHOOL_DISTRICT_ID[Schema::COLUMN]);
        $query->where(Schema::SCHOOL_ID, $schoolID);
        return $query->run();
    }

    public static function getSchool($schoolID) {
        $query = new Query(self::TABLE_NAME);
        $query->where(Schema::SCHOOL_ID, $schoolID);

        return $query->run()[0];
        \system\app\AppLogger::get()->debug("Get school by id: " . $schoolID);
        return(\system\Database::get()->query('SELECT * From ' . self::TABLE_NAME . ' Where ' . Schema::SCHOOL_ID[Schema::COLUMN] . '=' . $schoolID)[0]);
    }

    public static function deleteSchool($schoolID) {
        \system\app\AppLogger::get()->debug("Delete school id: " . $schoolID);
        return \system\Database::get()->query('DELETE FROM ' . self::TABLE_NAME . ' WHERE ' . Schema::SCHOOL_ID[Schema::COLUMN] . '=' . $schoolID);
    }

    public static function getADSettings($schoolID, $type) {
        $query = new Query(Schema::ACTIVEDIRECTORY);
        $query->where(Schema::ACTIVEDIRECTORY_SCHOOL_ID, $schoolID)
                ->where(Schema::ACTIVEDIRECTORY_TYPE, $type);

        return $query->run()[0];
    }

    public static function getGASettings($schoolID, $type) {
        $query = new Query(Schema::GOOGLEAPPS);
        $query->where(Schema::GOOGLEAPPS_SCHOOL_ID, $schoolID)
                ->where(Schema::GOOGLEAPPS_TYPE, $type);
        return $query->run()[0];
    }

}
