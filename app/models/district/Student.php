<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\district;

/**
 * Description of Student
 *
 * @author cjacobsen
 */
use app\models\district\User;
use app\api\AD;

class Student extends User {

    function __construct($username) {
        parent::__construct($username);
        $this->processAD(AD::get()->getStudentUser($username));
    }

}
