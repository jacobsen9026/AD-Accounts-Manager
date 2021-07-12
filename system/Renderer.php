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

namespace System;

/**
 * Description of Renderer
 *
 * @author cjacobsen
 */

use System\App\AppOutput;
use System\Models\View\DebugViewer;
use System\Models\Ajax\AJAXResponse;
use System\App\UserLogger;

class Renderer extends Parser
{

    public $output;

    /** @var Core|null */
    public $core;

    /** @var SystemLogger|null */
    public $logger;

    /** @var AppLogger|null
     * @deprecated since version number
     *  */
    public $appLogger = null;

    /** @var AppOutput */
    private $appOutput;

    function __construct(Core $core)
    {
        $this->core = $core;
        $this->logger = $core::$systemLogger;
        $this->appOutput = $core->appOutput;
    }

    public function draw()
    {

        switch (Request::get()->getType()) {
            case 'http':
                $this->include('system/views/HTML_start');
                $this->logger->info("Drawing of app started");

                if ($this->appOutput->getBody() !== null && $this->appOutput->getBody() != '') {
                    echo $this->appOutput->getBody();
                } else {
                    $this->showNoAppOutputWarning();
                }
                $this->logger->info("Drawing of app finished");
                $loggers = $this->getLoggers();
                if (!empty($loggers)) {
                    echo DebugViewer::printDebugTools($loggers);
                }
                $this->include('system/views/HTML_end');


                break;
            case 'ajax':

                $ajaxResponse = new AJAXResponse();
                if ($this->appOutput !== null) {
                    $ajaxResponse->importAppOutput($this->appOutput);
                }

                $ajaxResponse->addLogger($this->logger)
                    ->addLogger(DatabaseLogger::get())
                    ->addLogger(PostLogger::get())
                    ->addLogger(UserLogger::get());

                //var_dump($this->appOutput);
                $json = $ajaxResponse->jsonSerialize();
                //var_export($json);
                echo $json;

                break;

            default:
                break;
        }
        ob_flush();
        flush();

    }

    private function showNoAppOutputWarning()
    {
        $this->include('system/views/noAppOutput');
    }


    private function getLoggers()
    {

        $appLoggers = $this->appOutput->getLoggers();
        $loggers = [];
        if ($this->core->inDebugMode()) {
            $loggers = [SystemLogger::get(), PostLogger::get(), DatabaseLogger::get()];
        }
        $loggers = array_merge($loggers, $appLoggers);
        return $loggers;
    }

}

?>