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
use system\app\auth\Local;
use app\App;
use app\Session;
use system\app\auth\AuthException;

class Login extends Controller {

    //put your code here

    public function index() {

        //session_destroy();
        if ($this->postSet) {
            try {
                $user = Local::authenticate($_POST['username'], $_POST['password']);
                /* @var $ex AuthException */

                /** @var App|null The system logger */
                $app = App::get();
                $config = \app\config\MasterConfig::get();

                $app->user = $user;


                Session::setUser($user);
                Session::updateTimeout();

                header("Location: /");
            } catch (AuthException $ex) {
                session_destroy();
                return $ex->getMessage();
            }
        } else {
            return $this->view('login/index');
        }
    }

}
