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

namespace App\Models\District;

/**
 * Description of GoogleApps
 *
 * @author cjacobsen
 * @deprecated
 */

use app\database\Schema;

class GoogleApps
{

    //put your code here

    public static function getField($table, $id, $schema, $type)
    {
        $column = strtoupper($schema[Schema::COLUMN]);

        //\System\App\AppLogger::get()->debug($table . ' ' . $id . ' ' . $column . ' ' . $type);
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

    private static function getDistrictField($id, $column, $type)
    {
        $district = DistrictDatabase::getGASettings($id, $type);
        //\System\App\AppLogger::get()->debug($district);
        $schema = 'GOOGLEAPPS_' . $column;
        $constant = self::getSchemaColumn($schema);
        //\System\App\AppLogger::get()->debug($constant);
        $return = $district[$constant];
        return $return;
    }

    private static function getSchoolField($id, $column, $type)
    {

        \System\App\AppLogger::get()->info('Getting School ' . $id . ' Field ' . $column);
        $school = SchoolDatabase::getGASettings($id, $type);
        $districtID = SchoolDatabase::getDistrictID($id);
        //\System\App\AppLogger::get()->debug($school);
        $schema = 'GOOGLEAPPS_' . $column;
        $constant = self::getSchemaColumn($schema);
        //\System\App\AppLogger::get()->debug($constant);
        $return = $school[$constant];
        if ($return == false or $return == null or $return == '') {
            \System\App\AppLogger::get()->warning('Couldn\'t find school ' . $type . ' ' . $constant . ' will check district.');
            $return = self::getDistrictField($districtID, $column, $type);
        }
        return $return;
    }

    private static function getDepartmentField($id, $column, $type)
    {

        \System\App\AppLogger::get()->info('Getting Department ' . $id . ' Field ' . $column);
        $department = Department::getGASettings($id, $type);
        $schoolID = Department::getSchoolID($id);
        //\System\App\AppLogger::get()->debug($school);
        $schema = 'GOOGLEAPPS_' . $column;
        $constant = self::getSchemaColumn($schema);
        //\System\App\AppLogger::get()->debug($constant);
        $return = $department[$constant];
        if ($return == false or $return == null or $return == '') {
            \System\App\AppLogger::get()->warning('Couldn\'t find school ' . $type . ' ' . $constant . ' will check district.');
            $return = self::getSchoolField($schoolID, $column, $type);
        }
        return $return;
    }

    private static function getGradeField($id, $column, $type)
    {
        $grade = Grade::getGASettings($id, $type);

        $schoolID = Grade::getSchoolID($id);
        //var_dump($grade);
        $schema = 'GOOGLEAPPS_' . $column;
        $constant = self::getSchemaColumn($schema);
        //\System\App\AppLogger::get()->debug($constant);
        $return = $grade[$constant];
        if ($return == false or $return == null or $return == '') {
            if ($type == "Staff") {
                \System\App\AppLogger::get()->warning('Couldn\'t find grade ' . $type . ' ' . $constant . ' will check school.');
                $return = self::getSchoolField($schoolID, $column, $type);
            }
            if ($type == "Student") {
                \System\App\AppLogger::get()->warning('Couldn\'t find grade ' . $type . ' ' . $constant . ' will check staff grade settings.');
                $return = self::getSchoolField($id, $column, "Staff");
            }
        }
        return $return;
    }

    private static function getTeamField($id, $column, $type)
    {
        $team = Grade::getGASettings($id, $type);

        $gradeID = Team::getGradeID($id);
        var_dump($team);
        $schema = 'GOOGLEAPPS_' . $column;
        $constant = self::getSchemaColumn($schema);
        //\System\App\AppLogger::get()->debug($constant);
        $return = $team[$constant];
        if ($return == false or $return == null or $return == '') {
            \System\App\AppLogger::get()->warning('Couldn\'t find team ' . $type . ' ' . $constant . ' will check grade.');
            $return = self::getGradeField($gradeID, $column, $type);
        }
        return $return;
    }

    private static function getSchemaColumn($schema)
    {
        $schemaClass = new \ReflectionClass('app\database\Schema');
        //\System\App\AppLogger::get()->debug($schema);

        $constant = $schemaClass->getConstant($schema);
        //\System\App\AppLogger::get()->debug($constant);
        $column = $constant[Schema::COLUMN];
        //\System\App\AppLogger::get()->debug($column);
        return $column;
    }

}
