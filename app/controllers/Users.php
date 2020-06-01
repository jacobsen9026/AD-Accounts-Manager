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


use App\Models\Audit\Action\User\ResetUserPasswordAuditAction;
use App\Models\Audit\Action\User\SearchUserAuditAction;
use App\Models\District\DistrictUser;
use App\Models\User\PermissionHandler;
use App\Models\User\PermissionLevel;
use App\Models\View\Modal;
use App\Models\View\Toast;
use System\App\AppLogger;
use System\Post;
use System\App\Picture;
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

                    switch ($uploadedPicture->getType()) {


                        case 'image/png':
                            $picture = imagecreatefrompng($uploadedPicture->getTempFileName());
                            break;
                        case 'image/jpeg':
                        case 'image/jpgx':
                        case 'image/jpg':
                            $picture = imagecreatefromjpeg($uploadedPicture->getTempFileName());
                            break;

                        case 'image/gif':
                            $picture = imagecreatefromgif($uploadedPicture->getTempFileName());
                            break;
                        case 'image/bmp':
                            $picture = imagecreatefrombmp($uploadedPicture->getTempFileName());
                            break;

                    }
                    $picture = Picture::cropSquare($picture, 225);
                    ob_start();
                    imagejpeg($picture);
                    $rawPicture = ob_get_clean();

                    //var_dump(bin2hex($rawPicture));
                    $user = new DistrictUser($username);
                    $this->logger->debug($rawPicture);
                    $user->activeDirectory->setThumbnail($rawPicture, false)->save();
                    //imagecreatefromstring($uploadedPicture->getTempFileContents());
                    //$resiezedPhoto = imagescale($picture, '96', '96');
                    //imagejpeg($picture);
                }
                break;
            case 'resetPassword':
                $password = trim(Post::get("password"));
                $user = new DistrictUser(Post::get("username"));
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

    private function showAccountStatus($username)
    {

        $this->districtUser = $this->getUser($username);
        return $this->view('users/show');
    }

    public function getUser($username)
    {

        $user = new DistrictUser($username);
        //$user->activeDirectory;
        //var_dump($user);
        //var_dump($user->activeDirectory->getAttributes());
        return $user;

        //return new DistrictUser($username);
    }

    private function unlockUser($username)
    {
        $user = new DistrictUser($username);
        $user->activeDirectory->setClearLockoutTime()->save();
        $this->logger->debug($adUser);
        return $adUser;
    }

    public function accountStatusChangePost()
    {
        if ($action = Post::get("action")) {
            $username = Post::get("username");
            switch ($action) {
                case "unlock":
                    $this->unlockUser($username);
                    $this->student = $this->getUser($username);
                    return $this->view('staff/show/student');
                    break;
                case "lock":
                    break;

                default:
                    break;
            }
        }
    }

    public function editPost()
    {
//if (Post::csrfValid()) {
        $username = Post::get("username");
        $districtUser = $this->getUser($username);
        // var_dump($districtUser);
        $action = Post::get("action");
        //var_dump($action);

        if ($action != false) {
            switch ($action) {
                case "unlock":
                    if (PermissionHandler::hasPermission($districtUser->getOU(), PermissionLevel::USERS, PermissionLevel::USER_UNLOCK)) {

                        $districtUser->unlock();
                        $this->redirect('/users/search/' . $username);

                    }
                    return;


                case "enable":
                    if (PermissionHandler::hasPermission($districtUser->getOU(), PermissionLevel::USERS, PermissionLevel::USER_DISABLE)) {

                        $districtUser->enable();
                        $this->redirect('/users/search/' . $username);

                    }
                    return;

                case "disable";
                    if (PermissionHandler::hasPermission($districtUser->getOU(), PermissionLevel::USERS, PermissionLevel::USER_DISABLE)) {

                        $districtUser->disable();
                        $this->redirect('/users/search/' . $username);
                    }
                    return;

                default:
                    break;
            }
        }
    }

}
