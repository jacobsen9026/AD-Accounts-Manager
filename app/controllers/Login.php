<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

/**
 * Description of Login
 *
 * @author cjacobsen
 */
class Login extends Controller {

    //put your code here


    public function index() {
        if ($this->postSet) {
            var_dump($_POST);
        } else {
            return $this->view('login/index');
        }
    }

}
