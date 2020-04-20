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
use system\app\AppLogger;
use system\Post;
use app\auth\LDAP;

class Login extends Controller {

    //put your code here

    public function index() {
        $logger = AppLogger::get();
        $logger->debug('logining in');
        if (isset($_POST) and isset($_POST['username']) and isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            try {

                $logger->debug('trying local auth');
                /* @var $user User */
                $user = Local::authenticate($username, $password);
            } catch (AuthException $ex) {
                if ($ex->getMessage() == AuthException::BAD_PASSWORD) {
                    $this->lastErrorMessage = $ex->getMessage();
                    return $this->view('login/loginPrompt');
                }
                if (\app\models\Auth::getLDAPEnabled()) {
                    try {

                        $logger->debug('trying LDAP auth');
                        $user = LDAP::authenticate($username, $password);
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

            $logger->debug('Completed login');
            /** @var App|null The system logger */
            $app = App::get();
            //$config = MasterConfig::get();

            $app->user = $user;


            Session::setUser($user);
            Session::updateTimeout();

            $logger->debug('Referer: ' . $this->app->request->referer);
            $this->redirect($this->app->request->referer);
        } else {
            return $this->view('login/loginPrompt');
        }
    }

}
