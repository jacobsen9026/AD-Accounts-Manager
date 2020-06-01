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
use App\App\App;
use App\Models\User\User;
use App\App\Session;
use System\App\Auth\AuthException;
use System\App\AppLogger;
use App\Models\View\Toast;
use App\Auth\ADAuth;
use App\Models\Database\AuthDatabase;

class Login extends Controller
{

    public function __construct(App $app)
    {

        parent::__construct($app);
        $this->layout = 'login';
    }

    /**
     * Handle both display and processing of login page
     *
     * @return bool
     */
    public function index()
    {
        $logger = AppLogger::get();
        if (isset($_POST) && isset($_POST['username']) && isset($_POST['password'])) {
            $logger->debug('logining in');
            $username = $_POST['username'];
            $password = $_POST['password'];
            try {

                $logger->debug('trying local auth');
                /* @var $user User */
                $user = Local::authenticate($username, $password);
            } catch (AuthException $ex) {
                if ($ex->getMessage() == AuthException::BAD_PASSWORD) {
                    return $this->badCredentials();
                }
                if (AuthDatabase::getLDAPEnabled()) {
                    try {

                        $logger->debug('trying LDAP auth');
                        $adAuth = new ADAuth();
                        $user = $adAuth->authenticate2($username, $password);
                        //var_dump($user);


                    } catch (AuthException $ex) {
                        if ($ex->getMessage() == AuthException::BAD_PASSWORD) {
                            return $this->badCredentials();
                        }
                        return $this->badCredentials();
                    }
                }

            }
            if (!isset($user) or $user === null or $user === false) {

                return $this->badCredentials();
            }

            $logger->debug('Completed login');
            /** @var App|null The system logger */
            $app = App::get();
            $user->authenticated(true);
            $app->user = $user;


            Session::setUser($user);
            Session::updateTimeout();

            $logger->debug('Referer: ' . $this->app->request->referer);
            $this->redirect($this->app->request->referer);
        } else {
            return $this->view('login/loginPrompt');
        }
    }

    private function badCredentials()
    {
        $toast = new Toast('Bad Credentials', 'The username or password that you entered did not match', 3500);

        $toast->setImage('<i class="text-danger fas fa-exclamation-circle"></i>')
            ->bottom();
        $data = ['toast' => $toast->printToast()];

        return $this->view('login/loginPrompt', $data);
    }

}
