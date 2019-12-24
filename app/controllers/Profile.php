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
        echo "test";
        return $this->view('profile');
    }

    public function indexPost() {
        $post = \system\Post::getAll();
        setcookie("theme", $post["theme"]);
        return $this->view('profile');
    }

}
