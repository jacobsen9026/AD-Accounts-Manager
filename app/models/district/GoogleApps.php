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

    public static function getField($table, $id, $field, $type) {
        $field = $field[Schema::COLUMN];

        \system\app\AppLogger::get()->debug($table . ' ' . $id . ' ' . $field . ' ' . $type);
        switch ($table) {
            case Schema::DISTRICT:
                return self::getDistrictField($id, $field, $type);

                break;
            case Schema::SCHOOL:
                return self::getSchoolField($id, $field, $type);

                break;
            case Schema::GRADE:
                return self::getGradeField($id, $field, $type);

                break;
            case Schema::TEAM:
                return self::getTeamField($id, $field, $type);

                break;

            default:
                break;
        }
    }

    private static function getDistrictField($id, $field, $type) {
        $district = District::getGASettings($id, $type);
        \system\app\AppLogger::get()->debug($district);
        $schema = 'GOOGLEAPPS_' . $field;
        $constant = self::getSchemaColumn($schema);
        \system\app\AppLogger::get()->debug($constant);
        $return = $district[$constant];
        return $return;
    }

    private static function getSchoolField($id, $field, $type) {

        \system\app\AppLogger::get()->info('Getting School ' . $id . ' Field ' . $field);
        $school = School::getGASettings($id, $type);
        $districtID = School::getDistrictID($id);
        \system\app\AppLogger::get()->debug($school);
        $schema = 'GOOGLEAPPS_' . $field;
        $constant = self::getSchemaColumn($schema);
        \system\app\AppLogger::get()->debug($constant);
        $return = $school[$constant];
        if ($return == false or $return == null or $return == '') {
            \system\app\AppLogger::get()->warning('Couldn\'t find school ' . $constant);
            $return = self::getDistrictField($districtID, $field, $type);
        }
        return $return;
    }

    private static function getGradeField($id, $field, $type) {
        $grade = Grade::getGASettings($id, $type);

        $schoolID = Grade::getSchoolID($id);
        var_dump($grade);
        $schema = 'GOOGLEAPPS_' . $field;
        $constant = self::getSchemaColumn($schema);
        \system\app\AppLogger::get()->debug($constant);
        $return = $grade[$constant];
        if ($return == false or $return == null or $return == '') {
            \system\app\AppLogger::get()->warning('Couldn\'t find school ' . $constant);
            $return = self::getSchoolField($schoolID, $field, $type);
        }
        return $return;
    }

    private static function getTeamField($id, $field, $type) {
        $district = District::getGASettings($id, $type);
        \system\app\AppLogger::get()->debug($district);
        $schema = 'GOOGLEAPPS_' . $field;
        $constant = self::getSchemaColumn($schema);
        \system\app\AppLogger::get()->debug($constant);
        $return = $district[$constant];
        return $return;
    }

}
