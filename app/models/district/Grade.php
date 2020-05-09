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

namespace app\models\district;

/**
 * Description of Grade
 *
 * @author cjacobsen
 */
use app\database\Schema;
use app\models\Query;

class Grade {

    const TABLE_NAME = 'Grade';

    //put your code here
    public $gradeLevel;
    public $name;

    public static function getGrades($schoolID) {
        $query = new Query(self::TABLE_NAME);
        //var_dump($schoolID);
        $query->where(Schema::GRADE_SCHOOL_ID, $schoolID)
                ->leftJoin(Schema::GRADEDEFINITION, Schema::GRADE_GRADE_DEFINITION_ID, Schema::GRADEDEFINITION_VALUE);
        $result = 'no result';
        $result = $query->run();
        //var_dump($result);
        return $result;
    }

    public static function getGrade($gradeID) {
        $query = new Query(self::TABLE_NAME);
        //var_dump($schoolID);
        $query->where(Schema::GRADE_ID, $gradeID)
                ->leftJoin(Schema::GRADEDEFINITION, Schema::GRADE_GRADE_DEFINITION_ID, Schema::GRADEDEFINITION_VALUE);
        $result = 'no result';
        $result = $query->run();
        return $result[0];
        //return $result;
        return(\system\Database::get()->query('SELECT * From ' . self::TABLE_NAME . ' Where ' . Schema::GRADE_ID[Schema::COLUMN] . '=' . $gradeID)[0]);
    }

    public static function getSchoolID($gradeID) {
        $query = new Query(self::TABLE_NAME, Query::SELECT, Schema::GRADE_SCHOOL_ID[Schema::COLUMN]);
        $query->where(Schema::GRADE_ID, $gradeID);
        return $query->run();
    }

    public static function createGrade($schoolID, $post) {
        \system\app\AppLogger::get()->debug("Creating new grade for school: " . $schoolID);

        $post = \system\Post::getAll();
        var_dump($post);
        var_dump(Schema::GRADEDEFINITION_VALUE);
        $query = new Query(self::TABLE_NAME, Query::INSERT);
        $query->insert(Schema::GRADE_GRADE_DEFINITION_ID, $post[Schema::GRADEDEFINITION_VALUE[Schema::NAME]])
                ->insert(Schema::GRADE_SCHOOL_ID, $schoolID);
        return $query->run();
    }

    public static function deleteGrade($gradeID) {
        \system\app\AppLogger::get()->debug("Delete grade id: " . $gradeID);
        return \system\Database::get()->query('DELETE FROM ' . self::TABLE_NAME . ' WHERE ' . Schema::GRADE_ID[Schema::COLUMN] . '=' . $gradeID);
    }

    public static function getADSettings($gradeID, $type) {
        $query = new Query(Schema::ACTIVEDIRECTORY);
        $query->where(Schema::ACTIVEDIRECTORY_GRADE_ID, $gradeID)
                ->where(Schema::ACTIVEDIRECTORY_TYPE, $type);

        return $query->run()[0];
    }

    public static function getGASettings($gradeID, $type) {
        $query = new Query(Schema::GOOGLEAPPS);
        $query->where(Schema::GOOGLEAPPS_GRADE_ID, $gradeID)
                ->where(Schema::GOOGLEAPPS_TYPE, $type);

        return $query->run()[0];
    }

}
