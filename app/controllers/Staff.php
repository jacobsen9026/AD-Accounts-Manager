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
use App\Models\District\Student;
use App\Models\District\Staff as StaffModel;

class Staff extends Controller {

    public function index() {
        return $this->search();
    }

    /**
     *
     * @return type
     * @deprecated since version number
     */
    public function homeDrive() {
        return $this->view('staff/homeDrive');
    }

    /**
     *
     * @return type
     * @deprecated since version number
     */
    public function homeDrivePost() {
        $post = \system\Post::getAll();
        if (isset($post)and key_exists("action", $post) and $post["action"] == "query") {
            return $this->view('staff/show/homeDrive');
        }
    }

    /**
     *
     * @return html
     */
    public function accountStatus() {
        return $this->view('staff/accountStatus');
    }

    public function search($username = null) {
        if ($username == null) {
            return $this->view('staff/search');
        } else {
            //var_export($username);

            return $this->showAccountStatus($username);
        }
    }

    public function searchPost($username = null) {
        //return $username;

        return $this->search($username);
    }

    private function showAccountStatus($username) {

        $this->staff = $this->getStaff($username);
        return $this->view('staff/show/staff');
    }

    public function accountStatusPost() {
        $username = \system\Post::get("username");
        if ($username != false) {
            $this->staff = new StaffModel($username);
        }
        return $this->accountStatus();
    }

    private function getStaff($username) {
        $staff = new StaffModel($username);
        //var_dump($staff);
        return $staff;
//$gaUser = \App\Api\GAM::getUser($username);
    }

    private function unlockStudent($username) {
        $adUser = \App\Api\AD::get()->unlockUser($username);
        var_dump($adUser);
        return $adUser;
//$gaUser = \App\Api\GAM::getUser($username);
    }

    public function accountStatusChange() {

        return $this->view('staff/accountStatusChange');
    }

    public function accountStatusChangePost() {
        $post = \system\Post::getAll();
        if (isset($post)and key_exists("username", $post)) {
            $username = $post["username"];
            if (key_exists("action", $post)) {
                switch ($post["action"]) {
                    case "unlock":
                        $this->unlockStudent($username);
                        $this->student = $this->getStudent($post["username"]);
                        return $this->view('staff/show/student');
                        break;
                    case "lock":
                        break;

                    default:
                        break;
                }
            }

            //$this->student = $this->getStudent($post["username"]);
        }
        //return $this->view('staff/show/student');
    }

    public function resetPassword() {
        return $this->view('staff/resetPassword');
    }

    public function createAccounts() {
        return $this->view('staff/createAccounts');
    }

}
