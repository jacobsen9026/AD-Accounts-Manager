<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app\auth;

/**
 * Description of Local
 *
 * @author cjacobsen
 */
use system\app\auth\AuthException;

abstract class Local {

    //put your code here
    public function authenticate($username, $password) {
        if (strtolower($username) == "admin") {
            if ($password == "test") {
                return true;
            }
            throw new AuthException(AuthException::BAD_PASSWORD);
        }
        throw new AuthException(AuthException::BAD_USER);
    }

}
