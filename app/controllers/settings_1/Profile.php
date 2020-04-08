<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\settings;

/**
 * Description of User
 *
 * @author cjacobsen
 */
use app\controllers\Controller;

class Profile extends Controller {

    //put your code here
    public function index() {
        //echo "test";
        return $this->view('settings/profile');
    }

    public function indexPost() {
        $post = \system\Post::getAll();
        $this->setTheme($post["theme"]);
        return $this->view('/settings/profile');
    }

    private function setTheme($theme) {

        setcookie("theme", $theme);
        \system\app\Session::getUser()->setTheme($theme);
    }

}
