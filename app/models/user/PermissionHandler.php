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

namespace app\models\user;

/**
 * Description of PermissionHandler
 *
 * @author cjacobsen
 */
use app\models\user\User;
use app\models\user\PrivilegeLevel;
use app\models\user\Permission;
use system\app\AppLogger;

abstract class PermissionHandler {

    private static $superUser;

    /**
     *
     * @var AppLogger
     */
    private static $logger;

    /**
     * Determines if a user has the necessary permission to match the request
     * @param string $requestedType Outions are "user" or "group"
     * @param int $requestedLevel Options are int(0-4) Meaning differs based on permission type, but generally (Read,Change,Write,Delete)
     */
    public static function hasPermission(string $ou, string $requestedType, int $requestedLevel) {
        $testResults = array();

        if (self::$superUser) {
            return true;
        }
        $appClass = APPCLASS;
        /* @var $user User */
        $user = $appClass::get()->user;
        self::$logger = $appClass::get()->logger;
        self::$logger->info("Loading user permissions");


        $privlegeLevels = $user->getPrivilegeLevels();
        if (self::$superUser == null) {
            /* For backward compat until Privilege is deprecated */
            if ($user->privilege >= Privilege::TECH) {
                self::$superUser = true;
                return true;
            }
            foreach ($privlegeLevels as $level) {
                /* @var $level  PrivilegeLevel */
                if ($level->getSuperAdmin()) {
                    if ($user->privilege >= Privilege::TECH)
                        self::$superUser = true;
                }
            }
            if (self::$superUser == null) {
                self::$superUser = false;
            }
        }
        $userPermissions = $user->getPermissions($ou);


        //self::$logger->info($userPermissions);
        //var_dump($userPermissions);
        /* @var $permission Permission */
        foreach ($userPermissions as $permission) {

            //var_dump($permission->getOu());
            //var_dump($ou);
            $distanceFromOU = strpos($ou, $permission->getOu());
            self::$logger->info($permission->getId() . " Distance: " . $permission->getOu() . ' -> ' . $ou . ' = ' . $distanceFromOU);
            if (is_int($distanceFromOU)) {

                $method = 'get' . ucfirst(str_replace("_", '', $requestedType)) . "PermissionLevel";

                $usersPermissionLevel = $permission->$method();
                //var_dump($usersPermissionLevel);
                self::$logger->info("Checking permission: " . $requestedLevel . " -> " . $usersPermissionLevel);
                if ($usersPermissionLevel >= $requestedLevel) {
                    if (!key_exists($distanceFromOU, $testResults) or $testResults[$distanceFromOU] !== false) {
                        self::$logger->info("passed");
                        $testResults[$distanceFromOU] = true;
                    }
                } else {
                    self::$logger->info("failed");
                    $testResults[$distanceFromOU] = false;
                }
            }
        }
        //var_dump($testResults);
        if (empty($testResults)) {
            return false;
        } else {

            ksort($testResults);
            self::$logger->debug("Permission Test Results");
            self::$logger->debug($testResults);
            return $testResults[array_key_first($testResults)];
        }
    }

}
