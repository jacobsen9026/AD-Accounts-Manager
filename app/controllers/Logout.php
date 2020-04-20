<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

/**
 * Description of Logout
 *
 * @author cjacobsen
 */
class Logout extends Controller {

    public function index() {
        \system\app\Session::end();
        //session_destroy();
        header("Location: /");
    }

    //put your code here
}
