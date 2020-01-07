<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

/**
 * Description of Students
 *
 * @author cjacobsen
 */
use app\models\district\Student;

class Students extends Controller {

//put your code here
    public function homeDrive() {
        return $this->view('students/homeDrive');
    }

    public function homeDrivePost() {
        $post = \system\Post::getAll();
        if (isset($post)and key_exists("action", $post) and $post["action"] == "query") {
            return $this->view('students/show/homeDrive');
        }
    }

    public function accountStatus() {
        return $this->view('students/accountStatus');
    }

    public function accountStatusPost() {
        $post = \system\Post::getAll();
        if (isset($post)and key_exists("username", $post)) {

            $this->student = $this->getStudent($post["username"]);
        }
        return $this->view('students/show/student');
    }

    private function getStudent($username) {
        $adUser = \app\api\AD::get()->getStudentUser($username);
        var_dump($adUser);
        return $adUser;
//$gaUser = \app\api\GAM::getUser($username);
    }

    private function unlockStudent($username) {
        $adUser = \app\api\AD::get()->unlockUser($username);
        var_dump($adUser);
        return $adUser;
//$gaUser = \app\api\GAM::getUser($username);
    }

    public function accountStatusChange() {

        return $this->view('students/accountStatusChange');
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
                        return $this->view('students/show/student');
                        break;
                    case "lock":
                        break;

                    default:
                        break;
                }
            }

            //$this->student = $this->getStudent($post["username"]);
        }
        //return $this->view('students/show/student');
    }

    public function resetPassword() {
        return $this->view('students/resetPassword');
    }

    public function createAccounts() {
        return $this->view('students/createAccounts');
    }

}
