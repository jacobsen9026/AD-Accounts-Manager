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

namespace System\Common;

/**
 * Description of CommonLogEntry
 *
 *
 *
 * @author cjacobsen
 */

use System\Core;
use System\File;
use System\Log\CommonLogLevel;

class CommonLogEntry
{


    /**
     * @var mixed Timestamp in microseconds
     */
    private $timestamp;
    /**
     * @var string
     */
    private $level;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $backtrace;

    private $loggerName;

    private $logFile;

    public function __construct($loggerName = "")
    {
        $this->logFile = WRITEPATH . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . "debug.log";
        $this->timestamp = microtime(true);
        $this->backtrace = $this->traceBack();
        $this->loggerName = $loggerName;
    }

    /**
     * @return array
     */
    private function traceBack()
    {
        $traceCursor = 4;
        $cursor = 0;
        while (backTrace($traceCursor)) {
            $caller = backTrace($traceCursor);

            if (key_exists('file', $caller)) {
                $backTrace[$cursor] = ['file' => $caller["file"], 'line' => $caller["line"]];
            }
            $cursor++;
            $traceCursor++;
        }

        ksort($backTrace);
        return $backTrace;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return 'LogEntry_' . substr(hash("sha1", $this->getMessage() . $this->getTimestamp()), 0, 10);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     *
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $this->preProcessMessage($message);

        $this->writeToLogFile();
        return $this;
    }

    /**
     * @return string
     */
    public function getTimestamp()
    {
        return htmlspecialchars($this->timestamp);
    }

    /**
     *
     * @param mixed $message
     *
     * @return string
     *
     */
    private function preProcessMessage($message)
    {

        if (is_array($message)) {
            $message = var_export($message, true);
        }
        if (is_object($message)) {
            $message = $this->debugObject($message);
        }
        $message = str_replace("\n", "", $message);
        return $message;
    }

    /**
     *
     * @param object $object
     *
     * @return string
     */
    private function debugObject($object)
    {
        ob_start();
        if (isset($object->xdebug_message)) {
            ob_clean();
            return "<div class='jumbotron'>" . $object->xdebug_message . "</div>";
        }
        var_dump($object);
        return ob_get_clean();
    }

    /**
     *
     */
    private function writeToLogFile()
    {
        if (Core::inDebugMode()) {
            $logEntry = $this->loggerName . " " . $this->getTimestamp() . ' '
                . $this->getBacktrace()[0]['file'] . ':' . $this->getBacktrace()[0]['line']
                . ' ' . $this->getMessage() . "\n";
            if (DEBUG_FILE) {
                File::appendToFile($this->logFile, $logEntry . "\n");
            }
        }
    }

    /**
     * @return mixed
     */
    public function getBacktrace()
    {
        return $this->backtrace;
    }

    public function getAlertLevel()
    {
        switch ($this->getLevel()) {
            case CommonLogLevel::ERROR:
                return 'danger';
            case CommonLogLevel::DEBUG:
                return 'success';

            default:
                return $this->getLevel();
        }
    }

    /**
     * Returns one of the following
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     *
     * @param string $level
     *
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    public function isError()
    {
        if ($this->getLevel() == CommonLogLevel::ERROR) {
            return true;
        }
        return false;
    }

    public function getLoggerName()
    {
        return $this->loggerName;
    }

}
