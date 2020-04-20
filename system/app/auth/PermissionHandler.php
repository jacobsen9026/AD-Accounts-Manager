<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app\auth;

/**
 * Description of PermissionHandler
 *
 * @author cjacobsen
 */
use app\database\Schema;
use system\Database;
use system\app\App;
use app\models\Model;
use system\app\Route;

abstract class PermissionHandler extends Model {

    //put your code here
    const TABLE_NAME = "Permission";

    public static function handleRequest(Route $route, $user) {
        $route = $route->getControler() . '.' . $route->getMethod();
        $dbResonse = self::getRequiredPermission($route);
        if ($dbResonse === false) {
            self::addPermission($route, \app\models\user\Privilege::TECH);
            return \app\models\user\Privilege::TECH;
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
