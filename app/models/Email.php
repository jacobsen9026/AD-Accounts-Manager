<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

/**
 * Description of Auth
 *
 * @author cjacobsen
 */
use app\models\Query;

abstract class Email {

    const TABLE_NAME = 'Email';

    public static function get() {
        $query = new Query(self::TABLE_NAME);
        return $query->run()[0];
    }

//put your code here
    public static function getSessionTimeout() {
        return 1200;
    }

    public static function getTechGroup($type) {

    }

    public static function getAdminGroup($type) {

    }

    public static function getPowerGroup($type) {

    }

    public static function getBasicGroup($type) {

    }

    public static function getGroup($type) {

    }

}
