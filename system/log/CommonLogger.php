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

namespace System\Log;

/**
 * Description of CoreLogger
 *
 * @author cjacobsen
 */

use Psr\Log\LoggerInterface;
use System\Common\CommonLogEntry;
use System\Core;
use System\Traits\Parser;

class CommonLogger implements LoggerInterface
{
    use Parser;

    /**
     * @var array <CommonLogEntry> All the log entries
     */
    protected static $logEntries = [];
    /**
     * @var bool Error flag
     */
    protected $hasErrors = false;
    /**
     * @var float Logger starting time in microseconds
     */
    protected $startTime;
    /**
     * @var string Name of the logger
     */
    protected $name;

    /**
     * CommonLogger constructor.
     */
    function __construct()
    {
        $this->startTime = microtime(true);
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Returns the array of CommonLogEntries for this logger
     *
     * @return array<CommonLogEntry>
     */
    public function getLogEntries()
    {
        $returnArray = self::$logEntries;

//        usort($returnArray,
//            function ($a, $b) {
//                /* @var $a CommonLogEntry */
//                /* @var $b CommonLogEntry */
//                return $a->getTimestamp() < $b->getTimestamp();
//            });

        return $returnArray;
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function debug($message, array $context = [])
    {
        $this->storeLogEntry($message, CommonLogLevel::DEBUG);
    }

    /**
     * Stores a log event as a CommonLogEntry in this loggers $logEntries store
     *
     * @param mixed $message
     * @param string $level
     */
    protected function storeLogEntry($message, string $level)
    {
        if (Core::inDebugMode() && CommonLogLevel::NUMERIC_VALUE[$level] <= LOG_LEVEL) {
            $logEntry = new CommonLogEntry($this->getName());

            $logEntry->setMessage($message)
                ->setLevel($level);
            self::$logEntries[] = $logEntry;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function warning($message, array $context = [])
    {
        $this->storeLogEntry($message, CommonLogLevel::WARNING);
    }

    public function hasErrors()
    {
        return $this->hasErrors;
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function error($message, array $context = [])
    {
        $this->hasErrors = true;
        $this->storeLogEntry($message, CommonLogLevel::ERROR);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string|array $message
     * @param array $context
     *
     * @return void
     */
    public function info($message, array $context = [])
    {
        $this->storeLogEntry($message, CommonLogLevel::INFO);
    }


    /**
     * Check if this logger had anything logged
     *
     * @return boolean
     */
    public function hasLogEntries()
    {
        if (self::$logEntries !== null and !empty(self::$logEntries)) {
            return true;
        }
        return false;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function emergency($message, array $context = [])
    {
        $this->storeLogEntry($message, CommonLogLevel::EMERGENCY);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function alert($message, array $context = [])
    {
        $this->storeLogEntry($message, CommonLogLevel::ALERT);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function critical($message, array $context = [])
    {
        $this->storeLogEntry($message, CommonLogLevel::CRITICAL);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function notice($message, array $context = [])
    {
        $this->storeLogEntry($message, CommonLogLevel::NOTICE);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function log($level, $message, array $context = [])
    {
        $this->storeLogEntry($message, $level);
    }
}
