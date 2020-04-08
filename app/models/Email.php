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
use system\Database;

abstract class Email extends Model {

    const TABLE_NAME = 'Email';

    public static function get() {
        $query = new Query(self::TABLE_NAME);
        return $query->run()[0];
    }

    private static function getDBValue($column) {
        $query = new Query(self::TABLE_NAME, 'SELECT', $column);

        $query->where(Schema::EMAIL_APP_ID, App::getID());

        return $query->run();
    }

    public static function getSMTPServer() {
        return self::getDBValue(self::getColumnFromSchema(Schema::EMAIL_SMTP_SERVER));
    }

    public static function getSMTPUsername() {
        return self::getDBValue(self::getColumnFromSchema(Schema::EMAIL_SMTP_USERNAME));
    }

    public static function getSMTPPassword() {
        return self::getDBValue(self::getColumnFromSchema(Schema::EMAIL_SMTP_PASSWORD));
    }

    public static function getSMTPAuth() {
        return self::getDBValue(self::getColumnFromSchema(Schema::EMAIL_USE_SMTP_AUTH));
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
