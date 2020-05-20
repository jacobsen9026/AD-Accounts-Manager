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

namespace App\Models\Database;

/**
 * Description of Auth
 *
 * @author cjacobsen
 */
class AuthDatabase extends DatabaseModel {

    const TABLE_NAME = 'Auth';

    /**
     *
     * @return string The hashed local admin password
     */
    public static function getAdminPassword() {

        return self::getDatabaseValue("Admin_Password");
    }

    /**
     *
     * @return int Time in seconds
     */
    public static function getSessionTimeout() {
        return self::getDatabaseValue("Session_Timeout");
    }

    /**
     *
     * @return string
     * @deprecated since version number
     */
    public static function getTechGAGroup() {
        return self::getDatabaseValue('Tech_GA_Group');
    }

    /**
     *
     * @return string
     * @deprecated since version number
     */
    public static function getAdminGAGroup() {
        return self::getDatabaseValue('Admin_GA_Group');
    }

    /**
     *
     * @return string
     * @deprecated since version number
     */
    public static function getPowerGAGroup() {
        return self::getDatabaseValue('Power_GA_Group');
    }

    /**
     *
     * @return string
     * @deprecated since version number
     */
    public static function getBasicGAGroup() {
        return self::getDatabaseValue('Basic_GA_Group');
    }

    /**
     *
     * @return string
     */
    public static function getSuperUserADGroup() {
        return self::getDatabaseValue('Tech_AD_Group');
    }

    /**
     *
     * @return string
     */
    public static function getAdminADGroup() {
        return self::getDatabaseValue('Admin_AD_Group');
    }

    /**
     *
     * @return string
     */
    public static function getPowerADGroup() {
        return self::getDatabaseValue('Power_AD_Group');
    }

    /**
     *
     * @return string
     */
    public static function getBasicADGroup() {
        return self::getDatabaseValue('Basic_AD_Group');
    }

    /**
     *
     * @return bool
     */
    public static function getLDAPEnabled() {
        return self::getDatabaseValue('LDAP_Enabled');
    }

    /**
     *
     * @return bool
     */
    public static function getLDAPUseSSL() {
        return self::getDatabaseValue('LDAP_Use_SSL');
    }

    /**
     *
     * @return string
     */
    public static function getLDAPServer() {

        return self::getDatabaseValue('LDAP_Server');
    }

    /**
     *
     * @return string
     */
    public static function getLDAP_FQDN() {
        return self::getDatabaseValue('LDAP_FQDN');
    }

    /**
     *
     * @return int
     */
    public static function getLDAP_Port() {
        return self::getDatabaseValue('LDAP_Port');
    }

    /**
     *
     * @return string
     */
    public static function getLDAPUsername() {
        return self::getDatabaseValue('LDAP_Username');
    }

    /**
     *
     * @return string
     */
    public static function getLDAPPassword() {
        return self::getDatabaseValue('LDAP_Password');
    }

    public static function setAdminPassword(string $value) {
        if ($value != self::getAdminPassword()) {
            return self::updateDatabaseValue("Admin_Password", hash("sha256", $value));
        }
    }

    public static function setSessionTimeout(int $value) {

        return self::updateDatabaseValue("Session_Timeout", $value);
    }

    /**
     *
     * @param string $value
     * @return type
     * @deprecated since version number
     */
    public static function setTechGAGroup(string $value) {

        return self::updateDatabaseValue("Tech_GA_Group", $value);
    }

    /**
     *
     * @param string $value
     * @return type
     * @deprecated since version number
     */
    public static function setAdminGAGroup(string $value) {
        return self::updateDatabaseValue("Admin_GA_Group", $value);
    }

    /**
     *
     * @param string $value
     * @return type
     * @deprecated since version number
     */
    public static function setPowerGAGroup(string $value) {
        return self::updateDatabaseValue("Power_GA_Group", $value);
    }

    /**
     *
     * @param string $value
     * @return type
     * @deprecated since version number
     */
    public static function setBasicGAGroup(string $value) {
        return self::updateDatabaseValue("Basic_GA_Group", $value);
    }

    public static function setTechADGroup(string $value) {
        return self::updateDatabaseValue("Tech_AD_Group", $value);
    }

    public static function setAdminADGroup(string $value) {
        return self::updateDatabaseValue("Admin_AD_Group", $value);
    }

    public static function setPowerADGroup(string $value) {
        return self::updateDatabaseValue("Power_AD_Group", $value);
    }

    public static function setBasicADGroup(string $value) {
        return self::updateDatabaseValue("Basic_AD_Group", $value);
    }

    public static function setLDAPEnabled(bool $value) {
        return self::updateDatabaseValue("LDAP_Enabled", $value);
    }

    public static function setLDAPUseSSL(bool $value) {
        return self::updateDatabaseValue("LDAP_Use_SSL", $value);
    }

    public static function setLDAPServer(string $value) {
        return self::updateDatabaseValue("LDAP_Server", $value);
    }

    public static function setLDAP_FQDN(string $value) {
        return self::updateDatabaseValue("LDAP_FQDN", $value);
    }

    public static function setLDAP_Port(int $value) {
        return self::updateDatabaseValue("LDAP_Port", $value);
    }

    public static function setLDAPUsername(string $value) {
        return self::updateDatabaseValue("LDAP_Username", $value);
    }

    public static function setLDAPPassword(string $value) {
        return self::updateDatabaseValue("LDAP_Password", $value);
    }

    /**
     * Takes the raw Post data from the auth
     * setting form and translates it into
     * the database.
     *
     * @param type $postedData
     */
    public static function saveSettings(array $postedData) {
        foreach ($postedData as $key => $data) {
            \System\App\AppLogger::get()->debug($key);
            \System\App\AppLogger::get()->debug($data);
            switch ($key) {
                case "sessionTimeout":
                    self::setSessionTimeout($data);
                    break;

                case "adminPassword":
                    self::setAdminPassword($data);
                    break;

                case "ldapEnabled":
                    self::setLDAPEnabled($data);
                    break;

                case "ldapSSL":
                    self::setLDAPUseSSL($data);
                    break;

                case "ldapServer":
                    self::setLDAPServer($data);
                    break;

                case "ldapFQDN":
                    self::setLDAP_FQDN($data);
                    break;

                case "ldapPort":
                    self::setLDAP_Port($data);
                    break;

                case "ldapUsername":
                    self::setLDAPUsername($data);
                    break;

                case "ldapPassword":
                    self::setLDAPPassword($data);
                    break;

                case "ldapBasic":
                    self::setBasicADGroup($data);
                    break;

                case "ldapPower":
                    self::setPowerADGroup($data);
                    break;

                case "ldapAdmin":
                    self::setAdminADGroup($data);
                    break;

                case "ldapTech":
                    self::setTechADGroup($data);
                    break;

                default:
                    break;
            }
        }
    }

}
