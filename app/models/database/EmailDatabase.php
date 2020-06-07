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

use System\App\AppLogger;

/**
 * Description of Auth
 *
 * @author cjacobsen
 */
class EmailDatabase extends DatabaseModel
{

    const TABLE_NAME = 'Email';

    /**
     *
     * @return string
     */
    public static function getSMTPServer()
    {
        return self::getDatabaseValue("SMTP_Server");
    }

    /**
     *
     * @return string
     */
    public static function getSMTPUsername()
    {
        return self::getDatabaseValue("SMTP_Username");
    }

    /**
     *
     * @return string
     */
    public static function getSMTPPassword()
    {
        return self::getDatabaseValue("SMTP_Password");
    }

    /**
     *
     * @return string
     */
    public static function getSMTPPort()
    {
        $value = self::getDatabaseValue("SMTP_Port");
        if (!$value) {
            $value = 25;
        }
        return $value;
    }

    /**
     *
     * @return string
     */
    public static function getFromAddress()
    {
        return self::getDatabaseValue("From_Address");
    }

    /**
     *
     * @return string
     */
    public static function getFromName()
    {
        return self::getDatabaseValue("From_Name");
    }

    /**
     *
     * @return string
     */
    public static function getReplyToAddress()
    {
        return self::getDatabaseValue("Reply_To_Address");
    }

    /**
     *
     * @return string
     */
    public static function getReplyToName()
    {
        return self::getDatabaseValue("Reply_To_Name");
    }

    /**
     *
     * @return bool
     */
    public static function getUseSMTPSSL()
    {

        return self::getDatabaseValue("Use_SMTP_SSL");
    }

    /**
     *
     * @return bool
     *
     */
    public static function getUseSMTPAuth()
    {
        return self::getDatabaseValue("Use_SMTP_Auth");
    }

    /**
     * Takes the raw Post data from the auth
     * setting form and translates it into
     * the database.
     *
     * @param type $postedData
     */
    public static function saveSettings(array $postedData)
    {
        foreach ($postedData as $key => $data) {
            AppLogger::get()->debug($key);
            AppLogger::get()->debug($data);
            switch ($key) {
                case "fromAddress":
                    self::setFromAddress($data);
                    break;

                case "useSMTPAuth":
                    self::setUseSMTPAuth(boolval($data));
                    break;

                case "smtpPassword":
                    self::setSMTPPassword($data);
                    break;

                case "smtpUsername":
                    self::setSMTPUsername($data);
                    break;

                case "smtpServer":
                    self::setSMTPServer($data);
                    break;

                case "smtpPort":
                    self::setSMTPPort(intval($data));
                    break;

                case "useSMTPSSL":
                    self::setUseSMTPSSL(boolval($data));
                    break;

                case "fromName":
                    self::setFromName($data);
                    break;

                case "replyAddress":
                    self::setReplyToAddress($data);
                    break;

                case "replyName":
                    self::setReplyToName($data);
                    break;


                default:
                    break;
            }
        }
    }

    public static function setFromAddress(string $value)
    {
        return self::updateDatabaseValue("From_Address", $value);
    }

    public static function setUseSMTPAuth(bool $value)
    {
        return self::updateDatabaseValue("Use_SMTP_Auth", $value);
    }

    public static function setSMTPPassword(string $value)
    {
        return self::updateDatabaseValue("SMTP_Password", $value);
    }

    public static function setSMTPUsername(string $value)
    {
        return self::updateDatabaseValue("SMTP_Username", $value);
    }

    public static function setSMTPServer(string $value)
    {
        return self::updateDatabaseValue("SMTP_Server", $value);
    }

    public static function setSMTPPort(int $value)
    {

        return self::updateDatabaseValue("SMTP_Port", $value);
    }

    public static function setUseSMTPSSL(bool $value)
    {

        return self::updateDatabaseValue("Use_SMTP_SSL", $value);
    }

    public static function setFromName(string $value)
    {
        return self::updateDatabaseValue("From_Name", $value);
    }

    public static function setReplyToAddress(string $value)
    {
        return self::updateDatabaseValue("Reply_To_Address", $value);
    }

    public static function setReplyToName(string $value)
    {
        return self::updateDatabaseValue("Reply_To_Name", $value);
    }

    public function getUseSMTPEncryption()
    {
        return self::getDatabaseValue('Use_SMTP_Encyption');
    }

}
