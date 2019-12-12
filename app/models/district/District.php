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
class District {

//put your code here



    public $name;
    public $gradeSpan;
    public $abbreviation;

    /** @var array The last day of school. [month][day] * */
    public $lastDay;

    /** @var School The schools contained within this district */
    public $schools = null;

    function __construct($name = null) {
        $this->name = $name;
    }

    public function createSchool($name) {
        $this->schools[] = new School($name);
    }

}
