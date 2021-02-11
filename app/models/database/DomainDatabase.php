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

use System\App\AppLogger;
use System\Database;
use App\Models\District\Domain;
use System\Encryption;
use System\Traits\DomainTools;

class DomainDatabase extends DatabaseModel
{

    use DomainTools;

    const TABLE_NAME = "District";

    public static function createDomain($name)
    {
        AppLogger::get()->debug("Creating new district named: " . $name);
        return Database::get()->query('INSERT INTO ' . self::TABLE_NAME . ' (Name) VALUES ("' . $name . '")');
    }


    /**
     *
     * @param int $domainID
     *
     * @return Domain
     */
    public static function getDomain($domainID = 1)
    {
        $result = self::getDatabaseValue('*', $domainID)[0];

        $domain = new Domain();
        return $domain->importFromDatabase($result);
    }


    public static function getAD_BaseDN($domainID = 1)
    {
        $result = self::getDatabaseValue('AD_BaseDN', $domainID);
        if ($result == "false") {
            $result = self::FQDNtoDN(self::getAD_FQDN($domainID));
        }
        return $result;
    }

    public static function getAD_FQDN($domainID = 1)
    {
        return self::getDatabaseValue('AD_FQDN', $domainID);
    }

    public static function getADUsername($domainID = 1)
    {

        return self::getDatabaseValue('AD_Username', $domainID);
    }

    public static function getADPassword($domainID = 1)
    {
        return (string)Encryption::decrypt(self::getDatabaseValue('AD_Password', $domainID));
    }


    public static function setAD_FQDN($fqdn)
    {
        return self::updateDatabaseValue('AD_FQDN', $fqdn);
    }

    public static function setAbbreviation($abbr)
    {
        return self::updateDatabaseValue('Abbreviation', $abbr);
    }

    public static function setADBaseDN($baseDN)
    {
        return self::updateDatabaseValue('AD_BaseDN', $baseDN);
    }

    public static function setADNetBIOS($netBIOS)
    {
        return self::updateDatabaseValue('AD_NetBIOS', $netBIOS);
    }

    public static function setADUsername($username)
    {

        return self::updateDatabaseValue('AD_Username', $username);
    }

    public static function setADPassword($password)
    {


        if (Encryption::decrypt($password) === false) {
            $result = self::updateDatabaseValue('AD_Password', Encryption::encrypt($password));
        } else {
            $result = self::updateDatabaseValue('AD_Password', $password);
        }
        return $result;
    }


    public static function setName($name)
    {
        return self::updateDatabaseValue('Name', $name);
    }


    public static function getAD_UseTLS()
    {
        return self::getDatabaseValue('AD_Use_TLS', 1);
    }

    public static function setAD_UseTLS($useTLS)
    {
        self::updateDatabaseValue('AD_Use_TLS', $useTLS);
    }

}
