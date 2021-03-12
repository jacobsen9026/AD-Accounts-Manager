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
 * Handles the login process and view of the login page
 *
 * @author cjacobsen
 */

use App\Models\Audit\Action\AuditAction;
use App\Models\Audit\Action\System\UserLogonSuccessAuditAction;
use App\Models\Audit\Action\System\UserLogonAttemptAuditAction;
use App\Models\Audit\AuditEntry;
use App\Models\Database\AuditDatabase;
use App\Models\Database\UserDatabase;
use System\App\Auth\Local;
use App\App\App;
use App\Models\User\User;
use App\App\Session;
use System\App\Auth\AuthException;
use System\App\AppLogger;
use App\Models\View\Toast;
use App\Auth\ADAuth;
use App\Models\Database\AuthDatabase;
use System\Get;
use System\Post;

class Login extends Controller
{

    public function __construct(App $app)
    {

        parent::__construct($app);
        $this->layout = 'login';
    }

    public function indexPost()
    {
        return $this->index();
    }

    /**
     * Handle both display and processing of login page
     *
     * @return bool
     */
    public function index()
    {
        $logger = AppLogger::get();

        /**
         * Check if this is a POST request and has a username and password if so carry on and attempt a login
         * Otherwise the login prompt will be drawn
         */
        if (Post::isSet('username')) {

            if (Post::isSet('password')) {
                $logger->debug('logining in');
                $username = $_POST['username'];
                $password = $_POST['password'];
                try {

                    /**
                     * Attempt to authenticate local users (admin)
                     */
                    $logger->debug('trying local auth');
                    /* @var $user User */
                    $user = Local::authenticate($username, $password);
                } catch (AuthException $ex) {


                    /**
                     * Local auth failed
                     */
                    if ($ex->getMessage() == AuthException::BAD_PASSWORD) {


                        /**
                         * Admin password was incorrect
                         */
                        $this->audit(new UserLogonAttemptAuditAction($username));
                        return $this->badCredentials();
                    }


                    /**
                     * Wasn't an attempt with local admin
                     * Check if LDAP login is enabled
                     */
                    if (AuthDatabase::getLDAPEnabled()) {
                        try {
                            /**
                             * Attempt to login with LDAP
                             */
                            $logger->debug('trying LDAP auth');
                            $adAuth = new ADAuth();
                            $user = $adAuth->authenticate($username, $password);
                            //var_dump($user);


                        } catch (AuthException $ex) {
                            if ($ex->getMessage() == AuthException::BAD_PASSWORD) {

                                $this->audit(new UserLogonAttemptAuditAction($username));
                                return $this->badCredentials();
                            }

                            $this->audit(new UserLogonAttemptAuditAction($username));
                            return $this->badCredentials();
                        }
                    }

                }


                /**
                 * Check if the user is set otherwise something went wrong and just
                 * return bad credentials
                 */
                if (!isset($user) or $user === null or $user === false) {

                    return $this->badCredentials();
                }


                /**
                 * Wrap up login
                 */

                $logger->debug('Completed login');
                /** @var App|null The system logger */
                $app = App::get();
                $user->authenticated(true);
                $app->user = $user;


                Session::setUser($user);
                Session::updateTimeout();
                UserDatabase::initUser($user);
                $this->audit(new UserLogonSuccessAuditAction($user), $user);
                if (!Get::get('redirect') == false) {
                    $logger->debug('Redirecting: ' . Get::get('redirect'));
                    $this->redirect(Get::get('redirect'));

                } else {
                    $logger->debug('Referer: ' . $this->app->request->referer);
                    $this->redirect($this->app->request->referer);
                }
            } else {
                /*
                 * No password was sent
                 */
                return $this->badCredentials();
            }
        } else {
            /*
             * No username was sent
             */
            return $this->view('login/loginPrompt');
        }

    }

    /**
     * Overridden audit to provide a possible null user for login purposes
     * @param AuditAction $action
     * @param User|null $user
     */
    protected function audit(AuditAction $action, User $user = null)
    {
        $auditEntry = new AuditEntry($this->app->request, $user, $action);
        AuditDatabase::addAudit($auditEntry);
    }

    /**
     * Show a toast to the user indicating the credentials are bad
     * @return bool
     */
    private function badCredentials()
    {
        $toast = new Toast('Bad Credentials', 'The username or password that you entered did not match', 3500);

        $toast->setImage('<i class="text-danger fas fa-exclamation-circle"></i>')
            ->bottom();
        $data = ['toast' => $toast->printToast(), 'username' => Post::get("username")];

        return $this->view('login/loginPrompt', $data);
    }

}
