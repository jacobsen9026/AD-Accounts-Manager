<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\district;

/**
 * Description of ActiveDirectory
 *
 * @author cjacobsen
 */
use app\database\Schema;
use app\models\district\Table;

class ActiveDirectory {

    use Table;

    //put your code here

    public static function getField($table, $id, $schema, $type) {
        $column = $schema[Schema::COLUMN];

        \system\app\AppLogger::get()->debug($table . ' ' . $id . ' ' . $column . ' ' . $type);
        switch ($table) {
            case Schema::DISTRICT:
                return self::getDistrictField($id, $column, $type);

                break;
            case Schema::SCHOOL:
                return self::getSchoolField($id, $column, $type);

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
        $district = District::getADSettings($id, $type);
        \system\app\AppLogger::get()->debug($district);
        $schema = 'ACTIVEDIRECTORY_' . $column;
        $constant = self::getSchemaColumn($schema);
        \system\app\AppLogger::get()->debug($constant);
        $return = $district[$constant];
        return $return;
    }

    private static function getSchoolField($id, $colimn, $type) {

        \system\app\AppLogger::get()->info('Getting School ' . $id . ' Field ' . $colimn);
        $school = School::getADSettings($id, $type);
        $districtID = School::getDistrictID($id);
        \system\app\AppLogger::get()->debug($school);
        $schema = 'ACTIVEDIRECTORY_' . $colimn;
        $constant = self::getSchemaColumn($schema);
        \system\app\AppLogger::get()->debug($constant);
        $return = $school[$constant];
        if ($return == false or $return == null or $return == '') {
            \system\app\AppLogger::get()->warning('Couldn\'t find school ' . $constant);
            $return = self::getDistrictField($districtID, $colimn, $type);
        }
        return $return;
    }

    private static function getGradeField($id, $column, $type) {
        $grade = Grade::getADSettings($id, $type);

        $schoolID = Grade::getSchoolID($id);
        //var_dump($grade);
        $schema = 'ACTIVEDIRECTORY_' . $column;
        $constant = self::getSchemaColumn($schema);
        \system\app\AppLogger::get()->debug($constant);
        $return = $grade[$constant];
        if ($return == false or $return == null or $return == '') {
            \system\app\AppLogger::get()->warning('Couldn\'t find school ' . $constant);
            $return = self::getSchoolField($schoolID, $column, $type);
        }
        return $return;
    }

    private static function getTeamField($id, $column, $type) {
        $district = District::getADSettings($id, $type);
        \system\app\AppLogger::get()->debug($district);
        $schema = 'ACTIVEDIRECTORY_' . $column;
        $constant = self::getSchemaColumn($schema);
        \system\app\AppLogger::get()->debug($constant);
        $return = $district[$constant];
        return $return;
    }

}
