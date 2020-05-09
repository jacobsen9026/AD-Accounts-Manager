<?php

/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace app\models\database;

/**
 * Description of UserDatabase
 *
 * @author cjacobsen
 */
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
        $theme = $query->run();
        if (!$theme) {
            $theme = \app\config\Theme::DEFAULT_THEME;
        }
        return $theme;
    }

    public static function getID(String $username) {
        $query = new Query(self::TABLE_NAME, Query::SELECT, self::ID);
        $query->where(self::USERNAME, $username);
        return $query->run();
    }

}
