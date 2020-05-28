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

/**
 * Description of LogPrinter
 *
 * @author cjacobsen
 */

namespace System\Models\View;

use System\Common\CommonLogEntry;
use System\Core;
use System\Log\CommonLogger;

abstract class LogPrinter
{

    /**
     * @param CommonLogger $logger
     *
     * @return string
     */
    public static function printLog(CommonLogger $logger): string
    {
        $loggerOutput = '';
        if ($logger->hasLogEntries()) {
            $loggerOutput .= self::printLogEntries($logger->getLogEntries());
        }
        return $loggerOutput;
    }

    /**
     *
     * @param array $logEntries
     */
    private static function printLogEntries(array $logEntries): string
    {
        /*  @var $logEntry CommonLogEntry */
        $logOutput = '';
        foreach ($logEntries as $logEntry) {
            $logOutput .= self::printLogEntry($logEntry);
        }
        return $logOutput;
        //var_export($logOutput);
    }

    /**
     * @param CommonLogEntry $logEntry
     *
     * @return string
     */
    private static function printBackTrace(CommonLogEntry $logEntry): string
    {
        $traceOutput = '<div class="collapse bg-dark text-muted rounded p-3" id="' . $logEntry->getId() . '">'
            . 'Backtrace';
        $backtrace = $logEntry->getBacktrace();
        $x = count($backtrace);
        foreach ($backtrace as $step) {
            $traceOutput .= '<div class="row">'
                . '<div class="pl-2" style="max-width:2em">';
            $traceOutput .= $x;
            $traceOutput .= '</div>'
                . '<div class="col">';
            $traceOutput .= $step['file'] . ': ' . $step['line'];
            $traceOutput .= '</div>'
                . '</div>';
            $x--;
        }

        $traceOutput .= '</div>';
        return $traceOutput;
    }

    /**
     * @param CommonLogEntry $logEntry
     *
     * @return string
     */
    public static function printLogEntry(CommonLogEntry $logEntry): string
    {
        $logOutput = '';

        $et = substr($logEntry->getTimestamp(), strlen($logEntry->getTimestamp()) - 5, 5) . ' us';

        $entryOutput = ' <div class=" collapse show container-fluid mx-auto my-0 py-1 row rounded-0 alert alert-' . $logEntry->getAlertLevel() . '">
            <div class="small col-md-1">' . $et
            . '<div class="small">' . $logEntry->getLoggerName() . '</div></div>
            <div class="col-md-11 text-break ">

                <p class="clickable" data-toggle="collapse" data-target="#' . $logEntry->getId() . '" aria-expanded="false" aria-controls="">
                ' . $logEntry->getBacktrace()[0]['file'] . ":" . $logEntry->getBacktrace()[0]['line'] . "   " . $logEntry->getMessage() . '
                </p>
                ';
        $entryOutput .= self::printBackTrace($logEntry);
        $entryOutput .= '<!--backtrace here-->
            </div>
            </div>';
        $logOutput .= $entryOutput;

        return $logOutput;
    }

}
