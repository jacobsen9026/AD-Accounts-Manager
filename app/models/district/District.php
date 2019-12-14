<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\district;

/**
 * Description of District
 *
 * @author cjacobsen
 */
use system\Database;

class District {

//put your code here




    public static function createDistrict($name) {
        \system\app\AppLogger::get()->debug("Creating new district named: " . $name);
        return Database::get()->query('INSERT INTO District (Name) VALUES ("' . $name . '")');
    }

    public static function getDistricts() {
        return Database::get()->query('SELECT * FROM District');
    }

    public static function getDistrict($districtID) {

        return Database::get()->query('SELECT * FROM District WHERE ID = ' . $districtID)[0];
    }

    public static function deleteDistrict($districtID) {
        return Database::get()->query('DELETE FROM District WHERE ID = ' . $districtID);
    }

    public static function getSchools($districtID) {
        return Database::get()->query('SELECT * FROM Schools WHERE DistrictID = ' . $districtID);
    }

    public static function editDistrict($districtID, $post) {
        $abbr = $post['abbreviation'];
        $gradeSpanFrom = $post['gradeSpanFrom'];
        $gradeSpanTo = $post['gradeSpanTo'];

        return Database::get()->query('UPDATE District SET Abbreviation = "' . $abbr . '", GradeSpanFrom = "' . $gradeSpanFrom . '", GradeSpanTo = "' . $gradeSpanTo . '" WHERE ID = ' . $districtID);
    }

}
