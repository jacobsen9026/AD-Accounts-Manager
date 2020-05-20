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

namespace App\Controllers;

/**
 * Description of Default
 *
 * @author cjacobsen
 */
use System\Common\CommonController;
use App\Models\User\User;
use app\config\MasterConfig;
use System\App\AppLogger;
use app\database\Schema;
use App\Models\District\Grade;
use App\Models\Database\SchoolDatabase;
use App\Models\Database\DistrictDatabase;
use App\Models\District\Team;
use App\Models\District\Department;
use System\App\App;
use App\Models\Query;
use App\Models\District\District;

class Controller extends CommonController {

    use \System\App\RequestRedirection;

    //put your code here

    /** @var User|null The system logger */
    public $user;

    /** @var MasterConfig Description */
    public $config;

    /** @var District The district */
    public $district;

    /**
     *
     * @var AppLogger
     */
    protected $logger;

    /* @var $app App */

    function __construct(App $app) {

        parent::__construct($app);
        //$this->config = MasterConfig::get();
        //$this->controller = $app->route->getControler();
        $this->user = $app->user;
        $this->logger = $app->logger;
        $this->layout = "default";
    }

    public function preProcessSchoolID($schoolID) {
        $query = new Query(Schema::GRADEDEFINITION);

        $this->gradeDefinitions = $query->run();
        //var_dump($this->gradeDefinitions);
        $this->schoolID = $schoolID;
        $this->school = SchoolDatabase::getSchool($this->schoolID);

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
        $this->district = DistrictDatabase::getDistrict($this->districtID);
    }

    /**
     *
     * @return string
     */
    public function unauthorized() {
        return $this->view('errors/403');
    }

}

?>
