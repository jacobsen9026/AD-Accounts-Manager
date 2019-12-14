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
class School {

    //put your code here
    public $name;

    /** @var Grade The grades contained within this school */
    public $grades;

    function __construct($name) {
        $this->name = $name;
    }

    public static function createSchool($name, $districtID) {
        \system\app\AppLogger::get()->debug("Creating new district named: " . $name);
        return \system\Database::get()->query('INSERT INTO Schools (Name,DistrictID) VALUES ("' . $name . '", "' . $districtID . '")');
    }

    public static function getDistrictID($schoolID) {
        return(\system\Database::get()->query('SELECT DistrictID From Schools Where ID=' . $schoolID)[0]["DistrictID"]);
    }

    public static function getSchool($schoolID) {
        \system\app\AppLogger::get()->debug("Get school by id: " . $schoolID);
        return(\system\Database::get()->query('SELECT * From Schools Where ID=' . $schoolID)[0]);
    }

    public static function deleteSchool($schoolID) {
        \system\app\AppLogger::get()->debug("Delete school id: " . $schoolID);
        return \system\Database::get()->query('DELETE FROM Schools WHERE ID=' . $schoolID);
    }

    public static function editSchool($schoolID, $post) {

        \system\app\AppLogger::get()->debug("Modifying school id: " . $schoolID);
        $name = $post['name'];
        $staffGAOU = $post['staffGAOU'];
        $staffADOU = $post['staffADOU'];

        return \system\Database::get()->query('UPDATE Schools SET Name = "' . $name . '", StaffGAOU = "' . $staffGAOU . '", StaffADOU = "' . $staffADOU . '" WHERE ID = ' . $schoolID);
    }

}
