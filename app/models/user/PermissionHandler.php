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

namespace App\Models\User;

/**
 * Description of PermissionHandler
 *
 * @author cjacobsen
 */

use App\Models\User\User;
use App\Models\User\PrivilegeLevel;
use App\Models\User\Permission;
use System\App\AppLogger;
use App\Models\Database\PermissionMapDatabase;
use System\App\UserLogger;

abstract class PermissionHandler
{

    /**
     *
     * @var User
     */
    private static $user;

    /**
     *
     * @var array <PrivilegeLevels>
     */
    private static $privilegeLevels;

    /**
     *
     * @var AppLogger
     */
    private static $logger;

    private static function loadUser()
    {

        if (self::$user == null) {
            UserLogger::get()->info('Trying to load user for permission handling');
            $appClass = APPCLASS;
            self::$logger = $appClass::get()->logger;
            self::$user = $appClass::get()->user;
            UserLogger::get()->info('Loaded user:');
            UserLogger::get()->info(self::$user);
        }
    }

    /**
     * Determines if a user has the necessary permission to match the request
     *
     * @param string $requestedType Options are "user" or "group"
     * @param int $requestedLevel   Options are int(0-4) Meaning differs based on permission type, but generally
     *                              (Read,Change,Write,Delete)
     */
    public static function hasPermission(string $ou, string $requestedType, int $requestedLevel)
    {
        $testResults = [];


        self::loadUser();
        if (self::$user->superAdmin) {
            return true;
        }
        self::$logger->info("Loading user permissions");

        $userPermissions = self::$user->getPermissions($ou);


        /* @var $permission Permission */
        $testResults = self::testPermissions($userPermissions, $ou, $requestedType, $requestedLevel);

        if (empty($testResults)) {
            return false;
        } else {

            ksort($testResults);
            self::$logger->debug("Permission Test Results");
            self::$logger->debug($testResults);
            return $testResults[array_key_first($testResults)];
        }
    }

    /**
     * Checks if the user has any group permissions
     * defined anywhere within the permission mappings
     *
     * @return boolean
     */
    public static function hasGroupPermissions()
    {
        self::loadUser();
        if (self::$user->superAdmin) {
            return true;
        }
        if (self::$user->getPrivilegeLevels() !== null) {
            return PermissionMapDatabase::hasPermissionType(PermissionLevel::GROUPS, self::$user->getPrivilegeLevels(),);
        }
        UserLogger::get()->info("The user has no group permissions found.");
        return false;
    }

    /**
     * Checks if the user has any group permissions
     * defined anywhere within the permission mappings
     *
     * @return boolean
     */
    public static function hasUserPermissions()
    {
        self::loadUser();
        if (self::$user !== null) {
            if (self::$user->superAdmin) {
                return true;
            }
            if (self::$user->getPrivilegeLevels() !== null) {
                return PermissionMapDatabase::hasPermissionType(PermissionLevel::USERS, self::$user->getPrivilegeLevels(),);
            }
        }
        return false;
    }

    /**
     *
     * @param array $userPermissions An array of all the users relevant permissions for the requested OU
     * @param string $ou             The OU to find permission for
     * @param string $requestedType  Can be PermissionLevel::GROUPS or PermissionLevel::USERS
     * @param int $requestedLevel
     */
    private static function testPermissions(array $userPermissions, string $ou, string $requestedType, int $requestedLevel)
    {
        $testResults = [];
        foreach ($userPermissions as $permission) {
            $distanceFromOU = strpos($ou, $permission->getOu());
            self::$logger->info($permission->getId() . " Distance: " . $permission->getOu() . ' -> ' . $ou . ' = ' . $distanceFromOU);
            if (is_int($distanceFromOU)) {

                $method = 'get' . ucfirst(str_replace("_Perm", '', $requestedType)) . "PermissionLevel";

                $usersPermissionLevel = $permission->$method();
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
        return $testResults;
    }

}
