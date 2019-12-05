<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app\auth;

/**
 * Description of AuthException
 *
 * @author cjacobsen
 */
class AuthException extends \Exception {

    const BAD_PASSWORD = "BAD_PASSWORD";
    const BAD_USER = "BAD_USER";

    //put your code here
}
