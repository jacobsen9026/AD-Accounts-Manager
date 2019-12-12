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
abstract class Get {

    //put your code here
    public static function isSet() {
        if (isset($_POST) and $_POST != null) {
            return true;
        } else {
            return false;
        }
    }

    public static function getAll() {
        if (Post::isSet()) {
            return $_POST;
        } else {
            return null;
        }
    }

}
