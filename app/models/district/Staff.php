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
use app\models\district\Staff as StaffModel;

class Staff extends Controller {

//put your code here
    public function homeDrive() {
        return $this->view('staff/homeDrive');
    }

    public function homeDrivePost() {
        $post = \system\Post::getAll();
        if (isset($post)and key_exists("action", $post) and $post["action"] == "query") {
            return $this->view('staff/show/homeDrive');
        }
    }

    public function accountStatus() {
        return $this->view('staff/accountStatus');
    }

    public function accountStatusPost() {
        $post = \system\Post::getAll();
        if (isset($post)and key_exists("username", $post)) {
            $this->staff = new StaffModel($post["username"]);
        }
        return $this->view('staff/show/staff');
    }

    private function getStaff($username) {
        $gaUser = \app\api\GAM::get()->getUser($username);

        //var_dump($staff);
        return $staff;
//$gaUser = \app\api\GAM::getUser($username);
    }

    private function unlockStudent($username) {
        $adUser = \app\api\AD::get()->unlockUser($username);
        var_dump($adUser);
        return $adUser;
//$gaUser = \app\api\GAM::getUser($username);
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
