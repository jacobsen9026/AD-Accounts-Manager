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

namespace app\controllers;

/**
 * Description of Default
 *
 * @author cjacobsen
 */
use system\common\CommonController;
use app\models\user\User;
use app\config\MasterConfig;
use app\database\Schema;
use app\models\district\Grade;
use app\models\district\School;
use app\models\district\District;
use app\models\district\Team;
use app\models\district\Department;
use system\app\App;
use app\models\Query;

class Controller extends CommonController {
    //put your code here

    /** @var User|null The system logger */
    public $user;

    /** @var MasterConfig Description */
    public $config;

    /** @var string The controller string from the requested URL */
    public $controller;

    /* @var $app App */

    function __construct(App $app) {

        parent::__construct($app);
        //$this->config = MasterConfig::get();
        $this->controller = $app->request->controller;
        $this->user = $app->user;
        $this->layout = "default";
    }

    public function preProcessSchoolID($schoolID) {
        $query = new Query(Schema::GRADEDEFINITION);

        $this->gradeDefinitions = $query->run();
        //var_dump($this->gradeDefinitions);
        $this->schoolID = $schoolID;
        $this->school = School::getSchool($this->schoolID);

        $this->schoolName = $this->school[Schema::SCHOOL_NAME[Schema::COLUMN]];
        $grades = Grade::getGrades($this->schoolID);

        if (isset($grades) and $grades != false) {
            $this->grades = $grades;
        }
        $departments = Department::getDepartments($this->schoolID);
        if (isset($departments) and $departments != false) {
            $this->departments = $departments;
        }
        $this->districtID = $this->school[Schema::SCHOOL_DISTRICT_ID[Schema::COLUMN]];
        $this->preProcessDistrictID($this->districtID);
    }

    public function preProcessTeamID($teamID) {
        $this->teamID = $teamID;
        $this->team = Team::getTeam($this->teamID);
        $this->teamName = $this->team[Schema::TEAM_NAME[Schema::COLUMN]];
        $this->preProcessGradeID($this->team[Schema::TEAM_GRADE_ID[Schema::COLUMN]]);
    }

    public function preProcessGradeID($gradeID) {

        $this->gradeID = $gradeID;
        $this->grade = Grade::getGrade($this->gradeID);

        $schoolID = $this->grade[Schema::GRADE_SCHOOL_ID[Schema::COLUMN]];
        $this->teams = Team::getTeams($this->gradeID);
        $this->preProcessSchoolID($schoolID);
    }

    public function preProcessDepartmentID($departmentID) {

        $this->departmentID = $departmentID;
        $this->department = Department::getDepartment($this->departmentID);

        $this->departmentName = $this->department[Schema::DEPARTMENT_NAME[Schema::COLUMN]];
        $schoolID = $this->department[Schema::DEPARTMENT_SCHOOL_ID[Schema::COLUMN]];
        $this->preProcessSchoolID($schoolID);
    }

    public function preProcessDistrictID($districtID) {
        $this->districtID = $districtID;
        $this->district = District::getDistrict($this->districtID);
    }

    public function redirect($url) {
        if ($this->app->inDebugMode()) {
            $this->app->outputBody .= "In Debug Mode<br/>Would have redirected<br/>"
                    . "<a href='" . $url . "'>here</a>";
        } else {
            header('Location: ' . $url);
        }
    }

}

?>
