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

abstract class AppConfig {

//put your code here

    public static function getDebugMode() {
        return true;
    }

    public static function getAppName() {
        $query = 'SELECT ' . Schema::APP_NAME . ' FROM App WHERE ' . Schema::APP_ID . '=' . App::getID();
        $result = Database::get()->query($query);
        //var_dump($result);
        return $result;
    }

    public static function getAdminPassword() {
        $query = 'SELECT ' . Schema::APP_ADMIN_PASSWORD . ' FROM App WHERE ' . Schema::APP_ID . '=' . App::getID();
        $result = Database::get()->query($query);
        //var_dump($result);
        return $result;
    }

    public static function getMOTD() {
        $query = 'SELECT ' . Schema::APP_MOTD . ' FROM App WHERE ' . Schema::APP_ID . '=' . App::getID();
        $result = Database::get()->query($query);
        //var_dump($result);
        return $result;
    }

    public static function getForceHTTPS() {
        $query = 'SELECT ' . Schema::APP_FORCE_HTTPS . ' FROM App WHERE ' . Schema::APP_ID . '=' . App::getID();
        $result = Database::get()->query($query);
        //var_dump($result);
        return $result;
    }

    public static function getAdminUsernames() {
        $query = 'SELECT ' . Schema::APP_PROTECTED_ADMIN_USERNAMES . ' FROM App WHERE ' . Schema::APP_ID . '=' . App::getID();
        $result = Database::get()->query($query);
        $adminArray = unserialize($result);
        //var_dump($result);
        return $adminArray;
    }

    public static function getWebsiteFQDN() {
        $query = 'SELECT ' . Schema::APP_WEBSITIE_FQDN . ' FROM App WHERE ' . Schema::APP_ID . '=' . App::getID();
        $result = Database::get()->query($query);
        //var_dump($result);
        return $result;
    }

    public static function getUserHelpdeskURL() {
        $query = 'SELECT ' . Schema::APP_USER_HELPDESK_URL . ' FROM App WHERE ' . Schema::APP_ID . '=' . App::getID();
        $result = Database::get()->query($query);
        //var_dump($result);
        return $result;
    }

}
