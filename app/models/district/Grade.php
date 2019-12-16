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
 * Description of Grade
 *
 * @author cjacobsen
 */
use app\database\Schema;

class Grade {

    //put your code here
    public $gradeLevel;
    public $name;

    public static function getGrades($schoolID) {
        return(\system\Database::get()->query('SELECT * From Grades Where ' . Schema::GRADES_SCHOOL_ID . '=' . $schoolID));
    }

    public static function getGrade($gradeID) {
        return(\system\Database::get()->query('SELECT * From Grades Where ' . Schema::GRADES_ID . '=' . $gradeID)[0]);
    }

    public static function getSchoolID($gradeID) {
        return(\system\Database::get()->query('SELECT ' . Schema::GRADES_SCHOOL_ID . ' From Grades Where ' . Schema::SCHOOLS_ID . '=' . $gradeID)[0][Schema::GRADES_SCHOOL_ID]);
    }

    public static function createGrade($schoolID, $post) {
        \system\app\AppLogger::get()->debug("Creating new grade for school: " . $schoolID);
        return \system\Database::get()->query('INSERT INTO Grades (' . Schema::GRADES_VALUE . ',' . Schema::GRADES_SCHOOL_ID . ') VALUES ("' . $post[Schema::GRADES_VALUE] . '", "' . $schoolID . '")');
    }

    public static function deleteGrade($gradeID) {
        \system\app\AppLogger::get()->debug("Delete grade id: " . $gradeID);
        return \system\Database::get()->query('DELETE FROM Grades WHERE ' . Schema::GRADES_ID . '=' . $gradeID);
    }

}
