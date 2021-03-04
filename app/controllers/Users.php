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
 * Description of Students
 *
 * @author cjacobsen
 */


use App\Api\Ad\ADConnection;
use App\Api\Ad\ADUsers;
use App\Models\Audit\Action\User\CreateUserAuditAction;
use App\Models\Audit\Action\User\DisableUserAuditAction;
use App\Models\Audit\Action\User\EnableUserAuditAction;
use App\Models\Audit\Action\User\MoveUserAuditAction;
use App\Models\Audit\Action\User\ResetUserPasswordAuditAction;
use App\Models\Audit\Action\User\SearchUserAuditAction;
use App\Models\Audit\Action\User\UnlockUserAuditAction;
use App\Models\Audit\Action\User\UploadUserPhotoAudtitAction;
use App\Models\District\DomainUser;
use App\Models\User\PermissionHandler;
use App\Models\User\PermissionLevel;
use App\Models\View\Toast;
use System\App\AppException;
use System\App\AppLogger;
use System\App\LDAPLogger;
use System\Post;
use System\Models\Post\UploadedFile;

class Users extends Controller
{

    public function index()
    {
        return $this->search();
    }

    public function search($username = null)
    {
        if ($username == null) {
            return $this->view('users/search');
        } else {
            //var_export($username);
            $this->audit(new SearchUserAuditAction($username));
            return $this->showAccountStatus($username);
        }
    }

    private function showAccountStatus($username)
    {

        try {
            $this->districtUser = $this->getUser($username);
        } catch (AppException $ex) {
            $possibleUsers = ADUsers::listUsers($username);
            AppLogger::get()->debug($possibleUsers);
            if ($possibleUsers == null || empty($possibleUsers)) {
                throw $ex;
            }
            if (is_array($possibleUsers) && count($possibleUsers) == 1) {
                return $this->redirect('/users/search/' . $possibleUsers[0]);
                $this->districtUser = $this->getUser($possibleUsers[0]);
            } else {
                return $this->view('users/list', $possibleUsers);
            }
        }
        return $this->view('users/show');
    }

    private function getUser($username)
    {
        $user = new DomainUser($username);
        return $user;
    }

    public function searchPost($username = null)
    {
        $output = '';
        //return $username;
        $action = Post::get("action");
        switch ($action) {
            case 'uploadPhoto':
                $this->logger->info("Uploading a new picture for $username");

                $uploadedPicture = new UploadedFile(Post::getFile("photo"));
                $this->logger->debug($uploadedPicture);
                if ($uploadedPicture->exists()) {
                    $fileType = $uploadedPicture->getType();
                    $this->logger->debug('File type: ' . $fileType);
                    $uploadedPicture->resize(225);


                    $user = $this->getUser($username);

                    $user->activeDirectory->setThumbnail($uploadedPicture->getTempFileContents(), false)->save();

                    $this->audit(new UploadUserPhotoAudtitAction($username));

                }
                break;
            case 'resetPassword':
                $password = trim(Post::get("password"));
                $user = $this->getUser(Post::get("username"));
                if ($user->activeDirectory->setPassword($password)->save()) {
                    $this->logger->debug("password reset");
                    $this->audit(new ResetUserPasswordAuditAction($username));
                    $toast = new Toast('Password Reset Successfully', 'The password for ' . $username . ' has been changed', 3500);
                    $toast->setImage('<i class="fas fa-redo"></i>');
                    $output .= $toast->printToast();
                }
                break;
            default:
                break;
        }
        $output .= $this->search($username);
        return $output;
    }

    public function createPost()
    {
        if ($this->user->superAdmin) {
            $username = Post::get('logonname');
            $newUser = ADConnection::get()->getDefaultProvider()->make()->user()
                ->setAccountName($username);
            $newUser->setCommonName(Post::get('fullname'))
                ->setAttribute('givenName', Post::get('fname'))
                ->setAttribute('initials', Post::get('mname'))
                ->setAttribute('sn', Post::get('lname'))
                ->setDn('CN=' . Post::get('fullname') . ',' . Post::get('ou'))
                ->setAttribute('mail', Post::get('email'))
                ->setAttribute('description', Post::get('description'));
            $newUser->setPassword(Post::get('password'));
            // Get a new account control object for the user.
            $ac = $newUser->getUserAccountControlObject();

// Mark the account as enabled (normal).

            $ac->accountIsNormal();


// Set the account control on the user and save it.
// Set the account control on the user and save it.
            $newUser->setUserAccountControl($ac);
            LDAPLogger::get()->debug($newUser);
            $newUser->save();
            $user = new DomainUser($newUser->getAccountName());
            $this->audit(new CreateUserAuditAction($user));
            $this->redirect('/users/search/' . $user->getUsername());


        }
    }

    /**
     * Edit Post
     * This is the control for editing user account via the user search
     *
     * @throws \System\CoreException
     */
    public function editPost()
    {
        $username = Post::get("username");

        $action = Post::get("action");

        if ($action != false) {
            switch ($action) {
                case "unlock":
                    $domainUser = $this->getUser($username);
                    if (PermissionHandler::hasPermission($domainUser->getOU(), PermissionLevel::USERS, PermissionLevel::USER_UNLOCK)) {

                        $domainUser->unlock();
                        $this->audit(new UnlockUserAuditAction($username));
                        $this->redirect('/users/search/' . $username);

                    }
                    return;


                case "enable":
                    $domainUser = $this->getUser($username);
                    if (PermissionHandler::hasPermission($domainUser->getOU(), PermissionLevel::USERS, PermissionLevel::USER_DISABLE)) {

                        $domainUser->enable();

                        $this->audit(new EnableUserAuditAction($username));
                        $this->redirect('/users/search/' . $username);

                    }
                    return;

                case "disable";
                    $domainUser = $this->getUser($username);
                    if (PermissionHandler::hasPermission($domainUser->getOU(), PermissionLevel::USERS, PermissionLevel::USER_DISABLE)) {

                        $domainUser->disable();

                        $this->audit(new DisableUserAuditAction($username));
                        $this->redirect('/users/search/' . $username);
                    }
                    return;

                case "moveToOU";
                    $dn = Post::get("dn");
                    $ou = Post::get("ou");
                    if (PermissionHandler::hasPermission($ou, PermissionLevel::USERS, PermissionLevel::USER_CHANGE)) {
                        if (PermissionHandler::hasPermission($dn, PermissionLevel::USERS, PermissionLevel::USER_CHANGE)) {
                            $domainUser = new DomainUser(ADUsers::getUserByDN($dn));
                            $this->logger->info($domainUser);
                            $domainUser->moveTo($ou);

                            $this->audit(new MoveUserAuditAction($domainUser->getUsername(), $ou));
                            $this->redirect('/users/search/' . $domainUser->getUsername());
                        }

                    }

                    return;
                default:
                    break;
            }
        }
    }

}
