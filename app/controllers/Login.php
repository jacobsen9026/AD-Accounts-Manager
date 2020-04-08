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
use system\app\App;
use app\models\user\User;
use system\app\Session;
use system\app\auth\AuthException;
use system\Post;
use app\auth\LDAP;

class Login extends Controller {

    //put your code here

    public function index() {
        \system\app\AppLogger::get()->debug('logining in');
        if (Post::isSet()) {
            try {
                /* @var $user User */
                $user = Local::authenticate(Post::get('username'), Post::get('password'));
            } catch (AuthException $ex) {
                if ($ex->getMessage() == AuthException::BAD_PASSWORD) {
                    $this->lastErrorMessage = $ex->getMessage();
                    return $this->view('login/loginPrompt');
                }
                if (\app\models\Auth::getLDAPEnabled()) {
                    try {
                        $user = LDAP::authenticate(Post::get('username'), Post::get('password'));
                    } catch (AuthException $ex) {
                        if ($ex->getMessage() == AuthException::BAD_PASSWORD) {

                            $this->lastErrorMessage = $ex->getMessage();
                            return $this->view('login/loginPrompt');
                        }
                        $this->lastErrorMessage = $ex->getMessage();
                        return $this->view('login/loginPrompt');
                    }
                } else {
                    $this->lastErrorMessage = $ex->getMessage();
                    return $this->view('login/loginPrompt');
                }
            }
            /** @var App|null The system logger */
            $app = App::get();
            //$config = MasterConfig::get();

            $app->user = $user;


            Session::setUser($user);
            Session::updateTimeout();
            $this->redirect($this->app->request->referer);
        } else {
            return $this->view('login/loginPrompt');
        }
    }

}
