<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

/**
 * Description of AppConfig
 *
 * @author cjacobsen
 */
use app\database\Schema;
use system\Database;
use system\app\App;

abstract class AppConfig extends Model {

//put your code here
    const TABLE_NAME = 'App';

    public static function getDebugMode() {
        return true;
    }

    public static function getAppName() {
        $query = 'SELECT ' . self::getColumnFromSchema(Schema::APP_NAME) . ' FROM ' . self::TABLE_NAME . ' WHERE ' . self::getColumnFromSchema(Schema::APP_ID) . ' = ' . App::getID();
        //var_dump($query);
        $result = Database::get()->query($query);
        //var_dump($result);
        return $result;
    }

    public static function getAppAbbreviation() {
        $fullName = self::getAppName();
        $abbreviation = '';
        foreach (explode(" ", $fullName) as $word) {
            $abbreviation .= substr($word, 0, 1);
        }
        //var_dump($result);
        return $abbreviation;
    }

    public static function getAdminPassword() {
        $query = 'SELECT ' . self::getColumnFromSchema(Schema::APP_ADMIN_PASSWORD) . ' FROM ' . self::TABLE_NAME . ' WHERE ' . self::getColumnFromSchema(Schema::APP_ID) . ' = ' . App::getID();
        $result = Database::get()->query($query);
        //var_dump($result);
        return $result;
    }

    public static function getMOTD() {
        $query = 'SELECT ' . self::getColumnFromSchema(Schema::APP_MOTD) . ' FROM ' . self::TABLE_NAME . ' WHERE ' . self::getColumnFromSchema(Schema::APP_ID) . ' = ' . App::getID();
        $result = Database::get()->query($query);
        //var_dump($result);
        return $result;
    }

    public static function getForceHTTPS() {
        $query = 'SELECT ' . self::getColumnFromSchema(Schema::APP_FORCE_HTTPS) . ' FROM ' . self::TABLE_NAME . ' WHERE ' . self::getColumnFromSchema(Schema::APP_ID) . ' = ' . App::getID();
        $result = Database::get()->query($query);
        //var_dump($result);
        return $result;
    }

    public static function getAdminUsernames() {
        $query = 'SELECT ' . self::getColumnFromSchema(Schema::APP_PROTECTED_ADMIN_USERNAMES) . ' FROM ' . self::TABLE_NAME . ' WHERE ' . self::getColumnFromSchema(Schema::APP_ID) . ' = ' . App::getID();
        $result = Database::get()->query($query);
        $adminArray = unserialize($result);
        //var_dump($result);
        return $adminArray;
    }

    public static function getWebsiteFQDN() {
        $query = 'SELECT ' . self::getColumnFromSchema(Schema::APP_WEBSITIE_FQDN) . ' FROM ' . self::TABLE_NAME . ' WHERE ' . self::getColumnFromSchema(Schema::APP_ID) . ' = ' . App::getID();
        $result = Database::get()->query($query);
        //var_dump($result);
        return $result;
    }

    public static function getUserHelpdeskURL() {
        $query = 'SELECT ' . self::getColumnFromSchema(Schema::APP_USER_HELPDESK_URL) . ' FROM ' . self::TABLE_NAME . ' WHERE ' . self::getColumnFromSchema(Schema::APP_ID) . ' = ' . App::getID();
        $result = Database::get()->query($query);
        //var_dump($result);
        return $result;
    }

}
