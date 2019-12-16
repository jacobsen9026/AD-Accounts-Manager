<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

/**
 * Description of Auth
 *
 * @author cjacobsen
 */
abstract class Auth {

    //put your code here
    public static function getSessionTimeout() {
        return 1200;
    }

}
