<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\user;

/**
 * Description of Privilege
 *
 * @author cjacobsen
 */
use system\app\auth\CorePrivilege;

abstract class Privilege extends CorePrivilege {

    const BASIC = 1000;
    const POWER = 2000;
    const ADMIN = 3000;
    const TECH = 10000;

}
