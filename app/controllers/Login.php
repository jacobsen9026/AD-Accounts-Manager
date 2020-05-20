<?php

/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace App\Controllers;

/**
 * Description of Login
 *
 * @author cjacobsen
 */
use System\App\Auth\Local;
use System\App\App;
use App\Models\User\User;
use System\App\Session;
use System\App\Auth\AuthException;
use System\App\AppLogger;
use System\Post;
use App\Auth\ADAuth;
use App\Models\Database\AuthDatabase;

class Login extends Controller {

    public function __construct(App $app) {

        parent::__construct($app);
        $this->layout = 'login';
    }

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
                if (AuthDatabase::getLDAPEnabled()) {
                    try {

                        $logger->debug('trying LDAP auth');
                        $adAuth = new ADAuth();
                        $user = $adAuth->authenticate($username, $password);
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
