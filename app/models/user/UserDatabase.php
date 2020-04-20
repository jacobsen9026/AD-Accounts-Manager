<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\user;

/**
 * Description of UserDatabase
 *
 * @author cjacobsen
 */
use app\models\Query;
use app\models\DatabaseModel;

abstract class UserDatabase extends DatabaseModel {

    const TABLE_NAME = "User";
    const TOKEN = "Token";
    const USERNAME = "Username";
    const THEME = "Theme";
    const ID = "ID";
    const PRIVILEGE = "Privilege";

    //put your code here

    public static function setUserToken(String $username, String $token) {

        if (self::getID($username) == false) {
            $query = new Query(self::TABLE_NAME, Query::INSERT);
            $query->insert(self::USERNAME, $username);
            $query->insert(self::TOKEN, $token);
        } else {
            $query = new Query(self::TABLE_NAME, Query::UPDATE);
            $query->set(self::USERNAME, $username);
            $query->set(self::TOKEN, $token);
            $query->where(self::USERNAME, $username);
        }
        return $query->run();
    }

    public static function setUserTheme(String $username, String $theme) {

        if (self::getID($username) == false) {
            $query = new Query(self::TABLE_NAME, Query::INSERT);
            $query->insert(self::USERNAME, $username);
            $query->insert(self::THEME, $theme);
        } else {
            $query = new Query(self::TABLE_NAME, Query::UPDATE);
            $query->set(self::USERNAME, $username);
            $query->set(self::THEME, $theme);
            $query->where(self::USERNAME, $username);
        }
        return $query->run();
    }

    public static function setUserPrivilege(String $username, int $privilege) {

        if (self::getID($username) == false) {
            $query = new Query(self::TABLE_NAME, Query::INSERT);
            $query->insert(self::USERNAME, $username);
            $query->insert(self::PRIVILEGE, $privilege);
        } else {
            $query = new Query(self::TABLE_NAME, Query::UPDATE);
            $query->set(self::USERNAME, $username);
            $query->set(self::PRIVILEGE, $privilege);
            $query->where(self::USERNAME, $username);
        }
        return $query->run();
    }

    public static function getToken(String $username) {
        $query = new Query(self::TABLE_NAME, Query::SELECT, self::TOKEN);
        $query->where(self::USERNAME, $username);
        return $query->run();
    }

    public static function getTheme(String $username) {
        $query = new Query(self::TABLE_NAME, Query::SELECT, self::THEME);
        $query->where(self::USERNAME, $username);
        return $query->run();
    }

    public static function getID(String $username) {
        $query = new Query(self::TABLE_NAME, Query::SELECT, self::ID);
        $query->where(self::USERNAME, $username);
        return $query->run();
    }

}
