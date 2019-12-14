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
use system\common\CoreController;
use app\models\user\User;
use app\config\MasterConfig;
use app\database\Schema;
use app\models\district\Grade;
use app\models\district\School;
use app\models\district\District;

class Controller extends CoreController {
    //put your code here

    /** @var User|null The system logger */
    public $user;

    /** @var MasterConfig Description */
    public $config;

    function __construct($app) {

        parent::__construct($app);
        $this->config = MasterConfig::get();
        $this->layout = "default";
    }

    public function preProcessSchoolID($schoolID) {

        $this->schoolID = $schoolID;
        $this->school = School::getSchool($this->schoolID);
        $this->schoolName = School::getSchool($this->schoolID)[Schema::SCHOOLS_NAME];
        $this->grades = Grade::getGrades($this->schoolID);

        $this->districtID = $this->school[Schema::SCHOOLS_DISTRICTID];
        $this->preProcessDistrictID($this->districtID);
    }

    public function preProcessGradeID($gradeID) {
        $this->gradeID = $gradeID;
        $this->grade = Grade::getGrade($this->gradeID);
        $this->preProcessSchoolID($this->grade[Schema::GRADES_SCHOOLID]);
    }

    public function preProcessDistrictID($districtID) {
        $this->districtID = $districtID;
        $this->district = District::getDistrict($this->districtID);
    }

}

?>
