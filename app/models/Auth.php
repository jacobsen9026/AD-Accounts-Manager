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

    public static function getTechGAGroup() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_TECH_GA_GROUP[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

    public static function getAdminGAGroup() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_ADMIN_GA_GROUP[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

    public static function getPowerGAGroup() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_POWER_GA_GROUP[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

    public static function getBasicGAGroup() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_BASIC_GA_GROUP[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

    public static function getTechADGroup() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_TECH_AD_GROUP[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

    public static function getAdminADGroup() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_ADMIN_AD_GROUP[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

    public static function getPowerADGroup() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_POWER_AD_GROUP[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

    public static function getBasicADGroup() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_BASIC_AD_GROUP[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

    public static function getGroup($type) {

    }

    public static function getLDAPEnabled() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_LDAP_ENABLED[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

    public static function getLDAPUseSSL() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_LDAP_USE_SSL[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

    public static function getLDAPServer() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_LDAP_SERVER[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

    public static function getLDAP_FQDN() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_LDAP_FQDN[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

    public static function getLDAP_Port() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_LDAP_PORT[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

    public static function getLDAPUsername() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_LDAP_USERNAME[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

    public static function getLDAPPassword() {
        $query = new Query(self::TABLE_NAME, Query::SELECT, [Schema::AUTH_LDAP_PASSWORD[Schema::COLUMN]]);
        $query->where(Schema::AUTH_APP_ID, App::getID());
        return $query->run();
    }

}
