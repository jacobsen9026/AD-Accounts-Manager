<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\district;

/**
 * Description of GoogleApps
 *
 * @author cjacobsen
 */
use app\database\Schema;
use app\models\district\Table;

class GoogleApps {

    //put your code here
    use Table;

    //put your code here

    public static function getField($table, $id, $schema, $type) {
        $column = strtoupper($schema[Schema::COLUMN]);

        //\system\app\AppLogger::get()->debug($table . ' ' . $id . ' ' . $column . ' ' . $type);
        switch ($table) {
            case Schema::DISTRICT:
                return self::getDistrictField($id, $column, $type);

                break;
            case Schema::SCHOOL:
                return self::getSchoolField($id, $column, $type);

                break;
            case Schema::DEPARTMENT:
                return self::getDepartmentField($id, $column, $type);

                break;
            case Schema::GRADE:
                return self::getGradeField($id, $column, $type);

                break;
            case Schema::TEAM:
                return self::getTeamField($id, $column, $type);

                break;

            default:
                break;
        }
    }

    private static function getDistrictField($id, $column, $type) {
        $district = District::getGASettings($id, $type);
        //\system\app\AppLogger::get()->debug($district);
        $schema = 'GOOGLEAPPS_' . $column;
        $constant = self::getSchemaColumn($schema);
        //\system\app\AppLogger::get()->debug($constant);
        $return = $district[$constant];
        return $return;
    }

    private static function getSchoolField($id, $column, $type) {

        \system\app\AppLogger::get()->info('Getting School ' . $id . ' Field ' . $column);
        $school = School::getGASettings($id, $type);
        $districtID = School::getDistrictID($id);
        //\system\app\AppLogger::get()->debug($school);
        $schema = 'GOOGLEAPPS_' . $column;
        $constant = self::getSchemaColumn($schema);
        //\system\app\AppLogger::get()->debug($constant);
        $return = $school[$constant];
        if ($return == false or $return == null or $return == '') {
            \system\app\AppLogger::get()->warning('Couldn\'t find school ' . $type . ' ' . $constant . ' will check district.');
            $return = self::getDistrictField($districtID, $column, $type);
        }
        return $return;
    }

    private static function getDepartmentField($id, $column, $type) {

        \system\app\AppLogger::get()->info('Getting Department ' . $id . ' Field ' . $column);
        $department = Department::getGASettings($id, $type);
        $schoolID = Department::getSchoolID($id);
        //\system\app\AppLogger::get()->debug($school);
        $schema = 'GOOGLEAPPS_' . $column;
        $constant = self::getSchemaColumn($schema);
        //\system\app\AppLogger::get()->debug($constant);
        $return = $department[$constant];
        if ($return == false or $return == null or $return == '') {
            \system\app\AppLogger::get()->warning('Couldn\'t find school ' . $type . ' ' . $constant . ' will check district.');
            $return = self::getSchoolField($schoolID, $column, $type);
        }
        return $return;
    }

    private static function getGradeField($id, $column, $type) {
        $grade = Grade::getGASettings($id, $type);

        $schoolID = Grade::getSchoolID($id);
        //var_dump($grade);
        $schema = 'GOOGLEAPPS_' . $column;
        $constant = self::getSchemaColumn($schema);
        //\system\app\AppLogger::get()->debug($constant);
        $return = $grade[$constant];
        if ($return == false or $return == null or $return == '') {
            if ($type == "Staff") {
                \system\app\AppLogger::get()->warning('Couldn\'t find grade ' . $type . ' ' . $constant . ' will check school.');
                $return = self::getSchoolField($schoolID, $column, $type);
            }if ($type == "Student") {
                \system\app\AppLogger::get()->warning('Couldn\'t find grade ' . $type . ' ' . $constant . ' will check staff grade settings.');
                $return = self::getSchoolField($id, $column, "Staff");
            }
        }
        return $return;
    }

    private static function getTeamField($id, $column, $type) {
        $team = Grade::getGASettings($id, $type);

        $gradeID = Team::getGradeID($id);
        var_dump($team);
        $schema = 'GOOGLEAPPS_' . $column;
        $constant = self::getSchemaColumn($schema);
        //\system\app\AppLogger::get()->debug($constant);
        $return = $team[$constant];
        if ($return == false or $return == null or $return == '') {
            \system\app\AppLogger::get()->warning('Couldn\'t find team ' . $type . ' ' . $constant . ' will check grade.');
            $return = self::getGradeField($gradeID, $column, $type);
        }
        return $return;
    }

}
