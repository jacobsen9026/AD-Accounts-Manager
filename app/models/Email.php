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

    public static function getSMTPServer() {
        $query = 'SELECT ' . self::getColumnFromSchema(Schema::EMAIL_SMTP_SERVER) . ' FROM ' . self::TABLE_NAME . ' WHERE ' . self::getColumnFromSchema(Schema::APP_ID) . ' = ' . App::getID();
        $result = Database::get()->query($query);
        return $result;
        $query = new Query(self::TABLE_NAME);

        $query->where(Schema::ACTIVEDIRECTORY_DISTRICT_ID, $districtID)
                ->where(Schema::ACTIVEDIRECTORY_TYPE, $type);

        return $query->run()[0];
    }

    public static function getSMTPUsername() {
        $query = 'SELECT ' . self::getColumnFromSchema(Schema::EMAIL_SMTP_USERNAME) . ' FROM ' . self::TABLE_NAME . ' WHERE ' . self::getColumnFromSchema(Schema::APP_ID) . ' = ' . App::getID();

        $result = Database::get()->query($query);
        return $result;
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
