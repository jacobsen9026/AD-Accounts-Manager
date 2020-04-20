<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system;

/**
 * Description of Post
 *
 * @author cjacobsen
 */
use system\app\Session;
use system\AppException;

abstract class Post {

    //put your code here
    public static function isSet($key = null) {

        if ($key == null) {
            if (isset($_POST) and $_POST != null and!empty($_POST)) {
                return true;
            } else {
                return false;
            }
        } else {
            if (isset($_POST[$key]) and $_POST[$key] != null) {
                self::csrfCheck();
                return true;
            } else {
                return false;
            }
        }
    }

    public static function getAll() {
        if (Post::isSet()) {
            return $_POST;
        } else {
            return null;
        }
    }

    public static function get($key) {
        if (Post::isSet()) {
            if (Post::isSet($key)) {
                return $_POST[$key];
            }
        } else {
            throw new CoreException($key . ' not found in POST');
        }
    }

    public static function csrfCheck() {
        if (Post::isSet()) {
            if (isset($_POST["csrfToken"])) {
                if (Encryption::decrypt($_POST["csrfToken"]) == Session::getID()) {
                    return true;
                }
            }
        }
        throw new AppException("CSRF Invalid");
        //return false;
    }

}
