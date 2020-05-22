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
use App\Api\AD;
use System\Post;
use System\Get;

class Students extends Controller {

//put your code here

    public function index() {
        return $this->search();
    }

    public function homeDrivePost() {
        if (Post::get("action") == "query") {
            return $this->view('students/show/homeDrive');
        }
    }

    public function search($username = null) {
        if ($username == null) {
            return $this->view('students/search');
        } else {
//var_export($username);

            return $this->showAccountStatus($username);
        }
    }

    public function searchGet() {
        return $this->search(Get::get('username'));
    }

    public function searchPost($username = null) {
//return $username;
        return $this->search($username);
    }

    private function showAccountStatus($username) {

        $this->student = $this->getStudent($username);
        return $this->view('students/show/student');
    }

    private function getStudent($username) {


        $student = new Student($username);

//var_dump($student);
        return $student;
    }

    public function accountStatusChange() {

        return $this->view('students/accountStatusChange');
    }

    public function editPost() {
//if (Post::csrfValid()) {

        $username = Post::get("username");
        $student = $this->getStudent($username);
        $action = Post::get("action");
        if ($action != false) {
            switch ($action) {
                case "unlock":
                    $student->unlock();
                    return $this->showAccountStatus($username);


                case "enable":
                    $student->enable();
                    return $this->showAccountStatus($username);
                case "disable";
                    $student->disable();
                    return $this->showAccountStatus($username);
                    break;

                default:
                    break;
            }
        }
    }

    /**
     *
     * @return type
     * @deprecated since version number
     */
    public function resetPassword() {
        return $this->view('students/resetPassword');
    }

    public function resetPasswordPost() {
        $username = Post::get("username");
        $password = Post::get("password");
        return AD::get()->setPassword($username, $password);
    }

    /**
     *
     * @return type
     * @deprecated since version number
     */
    public function createAccounts() {
        return $this->view('students/createAccounts');
    }

}
