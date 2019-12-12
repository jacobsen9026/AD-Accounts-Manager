<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

/**
 * Description of User
 *
 * @author cjacobsen
 */
class Profile extends Controller {

    //put your code here
    public function index() {
        return $this->view('profile');
    }

}
