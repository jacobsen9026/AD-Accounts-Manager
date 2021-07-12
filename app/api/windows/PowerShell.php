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

namespace App\Api\Windows;

/**
 * Description of WindowsCommand
 *
 * @author cjacobsen
 */

use App\App\App;
use App\Models\Database\DomainDatabase;
use Exception;
use MTS\Factories;
use System\App\AppLogger;
use System\App\WindowsLogger;

class PowerShell
{
    const PSEXEC = APPPATH . DIRECTORY_SEPARATOR . "api" . DIRECTORY_SEPARATOR . "windows" . DIRECTORY_SEPARATOR . "psexec.exe";
    protected static $logger;
    /**
     * @var mixed|\MTS\Common\Devices\Shells\Bash|\MTS\Common\Devices\Shells\Cmd|\MTS\Common\Devices\Shells\PowerShell
     */
    private static $shell;
    private static $instance;
    private static $console = [];

    public function __construct()
    {
        self::$logger = WindowsLogger::get();
    }

    public static function isAvailable()
    {
        return true;
    }

    public static function getVersion()
    {
        $versionTable = self::run('$PSVersionTable', false);
        $psVersion = trim(explode("PSVersion", $versionTable)[1]);
        $psVersion = explode(" ", $psVersion)[0];
        //var_dump($versionTable);
        return ($psVersion);
    }

    public static function run($cmd = '', $withCredentials = true)
    {

        if (self::$instance == null) {
            new PowerShell();
        }
        if (self::$shell == null) {
            self::$shell = Factories::getDevices()->getLocalHost()->getShell('powershell');
            //var_dump(self::$shell);
        }
        if (App::inDebugMode()) {
            self::$shell->setDebug(true);
            //$this->shell->setDefaultExecutionTime(30000);
        }
        if ($withCredentials) {
            try {
                AppLogger::get()->debug(htmlspecialchars("\$password = ConvertTo-SecureString '" . DomainDatabase::getADPassword() . "' -AsPlainText -Force"));
                AppLogger::get()->debug('$credObject = New-Object System.Management.Automation.PSCredential ("' . DomainDatabase::getADUsername() . '", $password)');
                self::$shell->exeCmd("\$password = ConvertTo-SecureString '" . DomainDatabase::getADPassword() . "' -AsPlainText -Force");
                self::$shell->exeCmd('$credObject = New-Object System.Management.Automation.PSCredential ("' . DomainDatabase::getADUsername() . '", $password)');
                self::$logger->debug(htmlspecialchars($cmd) . " -Credential \$credObject");

                $response = self::$shell->exeCmd($cmd . " -Credential \$credObject");
            } catch (Exception $ex) {

                self::$logger->error($cmd);
            }
            if (strpos($response, "matches parameter name 'Credential'.") > 0) {
                try {
                    self::$logger->debug(htmlspecialchars($cmd) . " -DomainCredential \$credObject");

                    $response = self::$shell->exeCmd($cmd . " -DomainCredential \$credObject");
                } catch (Exception $ex) {

                    self::$logger->error($cmd);
                }
            }
            if (strpos($response, "matches parameter name 'DomainCredential'.") > 0 || strpos($response, "matches parameter name 'Credential'.") > 0) {
                try {
                    self::$logger->debug(htmlspecialchars($cmd));

                    $response = self::$shell->exeCmd($cmd);

                } catch (Exception $ex) {

                    self::$logger->error($cmd);
                }
            }
        } else {
            try {
                self::$logger->debug(htmlspecialchars($cmd));

                $response = self::$shell->exeCmd($cmd);

            } catch (Exception $ex) {

                self::$logger->error($cmd);
            }
        }

        self::addResponse($response);


        self::$logger->debug(self::getLastResponse());

        return self::getLastResponse();

    }

    protected static function addResponse($response)
    {
        self::$console[] = $response;
    }

    public static function getLastResponse()
    {
        return self::$console[sizeof(self::$console) - 1];
    }

    protected function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }


}
