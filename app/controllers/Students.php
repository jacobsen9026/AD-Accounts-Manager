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
class Students extends Controller {

    //put your code here
    public function homeDrive() {
        return $this->view('students/homeDrive');
    }

    public function accountStatus() {
        return $this->view('students/accountStatus');
    }

    public function accountStatusChange() {
        $this->actionArray = [["Unlock", "Unlock"]];
        if ($this->user->privilege >= \app\models\user\Privilege::TECH) {
            $this->actionArray[] = ["Lock", "lock"];
        }
        return $this->view('students/accountStatusChange');
    }

    public function resetPassword() {
        return $this->view('students/resetPassword');
    }

    public function createAccounts() {
        return $this->view('students/createAccounts');
    }

}
