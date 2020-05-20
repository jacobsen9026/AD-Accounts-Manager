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

namespace App\Models\District;

/**
 * Description of Grade
 *
 * @author cjacobsen
 */
use app\database\Schema;
use App\Models\Query;

class Team {

    const TABLE_NAME = 'Team';

    //put your code here
    public $gradeLevel;
    public $name;

    public static function getTeams($gradeID) {

        return(\system\Database::get()->query('SELECT * From Team Where ' . Schema::TEAM_GRADE_ID[Schema::COLUMN] . ' = ' . $gradeID));
    }

    public static function getTeam($teamID) {
        return(\system\Database::get()->query('SELECT * From Team Where ' . Schema::TEAM_ID[Schema::COLUMN] . ' = ' . $teamID)[0]);
    }

    public static function getGradeID($teamID) {
        $query = new Query(self::TABLE_NAME, Query::SELECT, Schema::TEAM_GRADE_ID[Schema::COLUMN]);
        $query->where(Schema::TEAM_ID, $teamID);
        return $query->run();
    }

    public static function createTeam($gradeID, $post) {
        \System\App\AppLogger::get()->debug("Creating new team for grade: " . $gradeID);
        return \system\Database::get()->query('INSERT INTO ' . self::TABLE_NAME . ' (' . Schema::TEAM_NAME[Schema::COLUMN] . ',' . Schema::TEAM_GRADE_ID[Schema::COLUMN] . ') VALUES ("' . $post[Schema::TEAM_NAME[Schema::COLUMN]] . '", "' . $gradeID . '")');
    }

    public static function deleteTeam($teamID) {
        \System\App\AppLogger::get()->debug("Delete grade id: " . $teamID);
        return \system\Database::get()->query('DELETE FROM  ' . self::TABLE_NAME . '  WHERE ID=' . $teamID);
    }

    public static function getADSettings($teamID, $type) {
        $query = new Query(Schema::ACTIVEDIRECTORY);
        $query->where(Schema::ACTIVEDIRECTORY_TEAM_ID, $teamID)
                ->where(Schema::ACTIVEDIRECTORY_TYPE, $type);

        return $query->run()[0];
    }

    public static function getGASettings($teamID, $type) {
        $query = new Query(Schema::GOOGLEAPPS);
        $query->where(Schema::GOOGLEAPPS_TEAM_ID, $teamID)
                ->where(Schema::GOOGLEAPPS_TYPE, $type);

        return $query->run()[0];
    }

}
