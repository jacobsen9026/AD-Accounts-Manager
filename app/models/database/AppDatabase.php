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
 * Description of AppConfig
 *
 * @author cjacobsen
 */
abstract class AppDatabase extends DatabaseModel {

    const TABLE_NAME = 'App';

    /**
     *
     * @return bool
     */
    public static function getDebugMode() {
        return self::getDatabaseValue("Debug_Mode");
    }

    /**
     *
     * @return string
     */
    public static function getAppName() {
        return self::getDatabaseValue("Name");
    }

    /**
     *
     * @return string
     */
    public static function getAppAbbreviation() {
        $fullName = self::getAppName();
        $abbreviation = '';
        foreach (explode(" ", $fullName) as $word) {
            $abbreviation .= substr($word, 0, 1);
        }
//var_dump($result);
        return $abbreviation;
    }

    /**
     *
     * @return string
     */
    public static function getMOTD() {
        return self::getDatabaseValue("MOTD");
    }

    /**
     *
     * @return bool
     */
    public static function getForceHTTPS() {

        return self::getDatabaseValue("Force_HTTPS");
    }

    /**
     *
     * @return type
     * @deprecated since version number
     */
    public static function getAdminUsernames() {
        return self::getDatabaseValue("Admin_Usernames");
    }

    /**
     *
     * @return type
     */
    public static function getWebsiteFQDN() {
        return self::getDatabaseValue("Websitie_FQDN");
    }

    /**
     *
     * @return type
     */
    public static function getUserHelpdeskURL() {
        return self::getDatabaseValue("User_Helpdesk_URL");
    }

    /**
     *
     * @param bool $value
     * @return bool
     */
    public static function setDebugMode($value) {
        return self::updateDatabaseValue('Debug_Mode', $value);
    }

    /**
     *
     * @param string $value
     * @return bool
     */
    public static function setAppName($value) {
        return self::updateDatabaseValue('Name', $value);
    }

    public static function setMOTD($value) {
        $value = str_replace("'", "''", $value);
        return self::updateDatabaseValue('MOTD', $value);
    }

    public static function setForceHTTPS($value) {
        return self::updateDatabaseValue('Force_HTTPS', $value);
    }

    /**
     *
     * @return type
     * @deprecated since version number
     */
    public static function setAdminUsernames($value) {
        return self::updateDatabaseValue('Admin_Usernames', $value);
    }

    /**
     * Update database column so it doesn't say websitie lol
     *
     * @param type $value
     * @return type
     */
    public static function setWebsiteFQDN($value) {
        return self::updateDatabaseValue('Websitie_FQDN', $value);
    }

    public static function setUserHelpdeskURL($value) {
        return self::updateDatabaseValue('User_Helpdesk_URL', $value);
    }

    public static function saveSettings($postedData) {
        foreach ($postedData as $key => $data) {
            $data = trim($data);
            \system\app\AppLogger::get()->debug($key);
            \system\app\AppLogger::get()->debug($data);
            switch ($key) {
                case "webAppName":
                    self::setAppName($data);
                    break;
                case "webMOTD":
                    self::setMOTD($data);
                    break;
                case "webAppFQDN":
                    self::setWebsiteFQDN($data);
                    break;
                case "forceHTTPS":
                    self::setForceHTTPS($data);
                    break;
                case "debugMode":
                    self::setDebugMode($data);
                    break;
                case "webHelpdeskURL":
                    self::setUserHelpdeskURL($data);
                    break;


                default:
                    break;
            }
        }
    }

}
