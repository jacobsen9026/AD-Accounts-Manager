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

namespace system;

/**
 * Description of Renderer
 *
 * @author cjacobsen
 */
class Renderer extends Parser {

//put your code here
    public $output;

    /** @var Core|null */
    public $core;

    /** @var SystemLogger|null */
    public $logger;

    /** @var AppLogger|null */
    public $appLogger = null;

    function __construct(Core $core) {
        $this->core = $core;
        $this->logger = $core->logger;
        $this->appLogger = $core->appLogger;
    }

    public function draw() {
//var_dump($this->core->appOutput);
        $this->include('system/views/HTML_start');
        $this->logger->info("Drawing of app started");
        if (isset($this->core->appOutput) and $this->core->appOutput != '') {
            echo $this->core->appOutput;
        } else {
            $this->showNoAppOutputWarning();
        }
        $this->logger->info("Drawing of app finished");
        $this->include('system/views/debugToolbar');
        $this->include('system/views/HTML_end');
    }

    private function showNoAppOutputWarning() {
        $this->include('system/views/noAppOutput');
    }

    public function errors_exists() {

        if (!empty($this->appLogger) and $this->appLogger->getLogs()['error'] !== null and sizeof($this->appLogger->getLogs()['error']) > 0) {
            return true;
        }
        if (!empty($this->logger) and $this->logger->getLogs()['error'] !== null and sizeof($this->logger->getLogs()['error']) > 0) {
            return true;
        }

        return false;
    }

}

?>