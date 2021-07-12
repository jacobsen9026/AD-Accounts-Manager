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
 * Description of DomainDatabase
 *
 *
 * @author cjacobsen
 */

use App\App\ConfigDatabase;
use System\App\AppLogger;
use System\Database;
use App\Models\Domain\Domain;
use System\Encryption;
use System\Traits\DomainTools;

class DomainDatabase extends DatabaseModel
{

    use DomainTools;

    const TABLE_NAME = "Domain";

    /**
     *
     * @param int $domainID
     *
     * @return Domain
     */
    public static function getDomain(int $domainID = 1)
    {
        $result = self::getDatabaseValue('*', $domainID);
        $domain = new Domain();
        if ($result != false) {
            $result = $result[0];

            $domain->importFromDatabase($result);
        }
        return $domain;

    }

    /**
     * @param int $domainID
     * @return mixed|string
     */
    public static function getAD_BaseDN(int $domainID = 1)
    {
        $result = self::getDatabaseValue('AD_BaseDN', $domainID);
        if ($result == "false") {
            $result = self::FQDNtoDN(self::getAD_FQDN($domainID));
        }
        AppLogger::get()->debug($result);
        return $result;
    }

    /**
     * @param int $domainID
     * @return mixed|string
     */
    public static function getAD_FQDN(int $domainID = 1)
    {
        return self::getDatabaseValue('AD_FQDN', $domainID);
    }

    /**
     * @param int $domainID
     * @return mixed|string
     */
    public static function getADUsername(int $domainID = 1)
    {

        return self::getDatabaseValue('AD_Username', $domainID);
    }

    /**
     * @param int $domainID
     * @return string
     */
    public static function getADPassword(int $domainID = 1)
    {
        return (string)Encryption::decrypt(self::getDatabaseValue('AD_Password', $domainID));
    }

    /**
     * @param string $fqdn
     * @return string
     */
    public static function setAD_FQDN(string $fqdn)
    {
        return self::updateDatabaseValue('AD_FQDN', $fqdn);
    }

    /**
     * @param string $abbr
     * @return string
     */
    public static function setAbbreviation(string $abbr)
    {
        return self::updateDatabaseValue('Abbreviation', $abbr);
    }

    /**
     * @param string $baseDN
     * @return string
     */
    public static function setADBaseDN(string $baseDN)
    {
        return self::updateDatabaseValue('AD_BaseDN', $baseDN);
    }

    /**
     * @param string $netBIOS
     * @return string
     */
    public static function setADNetBIOS(string $netBIOS)
    {
        return self::updateDatabaseValue('AD_NetBIOS', $netBIOS);
    }

    /**
     * @param string $username
     * @return string
     */
    public static function setADUsername(string $username)
    {

        return self::updateDatabaseValue('AD_Username', $username);
    }

    /**
     * @param string $password
     * @return string
     */
    public static function setADPassword(string $password)
    {


        if (Encryption::decrypt($password) === false) {
            $result = self::updateDatabaseValue('AD_Password', Encryption::encrypt($password));
        } else {
            $result = self::updateDatabaseValue('AD_Password', $password);
        }
        return $result;
    }

    /**
     * @param string $name
     * @return string
     */
    public static function setName(string $name)
    {
        return self::updateDatabaseValue('Name', $name);
    }

    /**
     * @param int $domainID
     * @return mixed|string
     */
    public static function getAD_UseTLS(int $domainID = 1)
    {
        $return = self::getDatabaseValue('AD_Use_TLS', $domainID);
        if ($return == null) {
            return false;
        }
        return $return;
    }

    /**
     * Initialize database if it doesn't exist
     */
    public static function init()
    {
        if (self::get() === false) {
            self::createDomain('');
        }

    }

    /**
     * @param string $name
     * @return array|bool
     * @throws \System\App\AppException
     */
    public static function createDomain(string $name)
    {
        AppLogger::get()->debug("Creating new domain named: " . $name);
        return ConfigDatabase::get()->query('INSERT INTO ' . self::TABLE_NAME . ' (ID) VALUES (1)');
    }

    /**
     * @param bool $useTLS
     */
    public static function setAD_UseTLS(bool $useTLS)
    {
        self::updateDatabaseValue('AD_Use_TLS', $useTLS);
    }

}
