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

namespace system\app\auth;

/**
 * Description of PermissionHandler
 *
 * @author cjacobsen
 * @deprecated since version number
 */
use app\database\Schema;
use system\Database;
use app\models\Model;
use system\app\Route;
use app\models\user\Privilege;

abstract class Permission extends Model {

    //put your code here
    const TABLE_NAME = "Permission";

    public static function handleRequest(Route $route, $user) {
        $route = $route->getControler() . '.' . $route->getMethod();
        $dbResonse = self::getRequiredPermission($route);
        if ($dbResonse === false) {
            self::addPermission($route, Privilege::TECH);
            return Privilege::TECH;
        }
        return $dbResonse;
    }

    private static function getRequiredPermission($uri) {
        $query = 'SELECT ' . self::getColumnFromSchema(Schema::PERMISSION_REQUIRED_PERMISSION) . ' FROM ' . self::TABLE_NAME . ' WHERE ' . self::getColumnFromSchema(Schema::PERMISSION_PATH) . ' = "' . $uri . '"';
        //var_dump($query);
        $result = Database::get()->query($query);
        //var_dump($result);
        return $result;
    }

    private static function addPermission($uri, $level) {
        $query = 'INSERT INTO ' . self::TABLE_NAME . '(' . self::getColumnFromSchema(Schema::PERMISSION_PATH) . ',' . self::getColumnFromSchema(Schema::PERMISSION_REQUIRED_PERMISSION) . ') VALUES ("' . $uri . '", "' . $level . '")';
        //var_dump($query);
        $result = Database::get()->query($query);
        //var_dump($result);
        return $result;
    }

}
