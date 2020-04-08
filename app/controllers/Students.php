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
use app\api\AD;
use system\Post;

class Students extends Controller {

//put your code here
    public function homeDrivePost() {
        $post = \system\Post::getAll();
        if (isset($post)and key_exists("action", $post) and $post["action"] == "query") {
            return $this->view('students/show/homeDrive');
        }
    }

    public function accountStatus($username=null) {
        if ($username==null){
        return $this->view('students/accountStatus');
    }
    else{
        //var_export($username);
        
            return $this->showAccountStatus($username);
        }
        
    }

    public function accountStatusPost() {
        $post = Post::getAll();
        if (isset($post)and key_exists("username", $post)) {
            return $this->showAccountStatus($post["username"]);
            
        }
    }
    public function accountStatusGet() {
        $get = Get::getAll();
        if (isset($get)and key_exists("username", $get)) {
            return $this->showAccountStatus($post["username"]);
        }
    }
    private function showAccountStatus($username){
        
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

    public function accountStatusChangePost() {
        $post = Post::getAll();
        if (isset($post)and key_exists("username", $post)) {
            $username = $post["username"];
            $student = $this->getStudent($username);
            if (key_exists("action", $post)) {
                switch ($post["action"]) {
                    case "unlock":
                        $student->unlock();
                        return $this->showAccountStatus($username);
                        
                    
                    case "enable":
                        $student->enable();
                       // var_dump($username);
                        return $this->showAccountStatus($username);
                    case "disable";
                         $student->disable();
                       // var_dump($username);
                        return $this->showAccountStatus($username);
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

    public function resetPasswordPost() {
        $username = Post::get("username");
        $password = Post::get("password");
        return AD::get()->setPassword($username, $password);
    }

    public function createAccounts() {
        return $this->view('students/createAccounts');
    }

}
