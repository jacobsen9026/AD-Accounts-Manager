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
use app\database\Schema;
use system\app\App;

abstract class Auth {

    const TABLE_NAME = 'Auth';

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

    public static function getLDAPEnabled() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_LDAP_ENABLED[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

}
