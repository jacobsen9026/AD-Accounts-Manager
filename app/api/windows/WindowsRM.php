<?php

/*
 * The MIT License
 *
 * Copyright 2019 cjacobsen.
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
 * Description of WindowsRM
 *
 * @author cjacobsen
 */

use System\App\AppLogger;
use System\App\AppException;

class WindowsRM
{

    /**
     *
     * @var WindowsRM
     */
    public static $instance;
    /**
     *
     * @var AppLogger
     */
    private $logger;

    function __construct()
    {

        if (isset(self::$instance)) {
            return self::$instance;
        } else {
            $this->logger = AppLogger::get();
            self::$instance = $this;
        }
    }

    /**
     *
     * @return WindowsRM
     */
    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function reboot(string $destination)
    {
        $cmd = new WindowsCommand();
        $cmd->setCmd('shutdown -r -m ' . $destination . ' -t 60');
        AppLogger::get()->info('Rebooting ' . $destination);
    }

    public function testConnection($computer)
    {
        $this->logger->info("Testing connection to $computer");
        try {
            if (!filter_var($computer, FILTER_VALIDATE_IP)) {

                $this->logger->info("Failed IP validation, performing DNS lookup");

                $computer = $this->DNSLookup($computer);
                $this->logger->info($computer);
            }
            if (filter_var($computer, FILTER_VALIDATE_IP)) {
                return $this->portReachable($computer, 5985);
            }
        } catch (\Exception $ex) {
            return false;
        }

        //return $this->portReachable($computer, 5985);
    }

    public function DNSLookup($hostname)
    {
        $record = dns_get_record($hostname);
        if (is_array($record) and key_exists(0, $record) and is_array($record[0]) and key_exists('ip', $record[0])) {
            return $record[0]['ip'];
        }
        throw new AppException("Could not resolve DNS name");
    }

    private function portReachable($host, $port)
    {
        $wait = 1;
        $socket = @\fsockopen($host, $port, $errCode, $errStr, $wait);
        //echo "Ping $host:$port ($key) ==> ";
        if ($socket) {
            //echo 'SUCCESS';
            fclose($socket);
            return true;
        } else {
            $this->logger->warning("ERROR: $errCode - $errStr");
        }
        return false;
        //echo PHP_EOL;
    }

    public function rebootWorkstation($hostname)
    {

        $cmd = new WindowsCommand();
        $cmd->setCmd("shutdown /r /t 30")
            ->setHostname($hostname);
    }

}
