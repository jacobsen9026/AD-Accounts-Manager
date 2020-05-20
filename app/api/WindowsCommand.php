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

namespace App\Api;

/**
 * Description of WindowsCommand
 *
 * @author cjacobsen
 */
use System\Core;

class WindowsCommand {

    private $hostname;
    private $cmd;
    private $response;
    private $returnCode;
    private $logger;

    public function __construct() {
        $this->setLogger(WindowsLogger::get());
    }

    public function getHostname() {
        return $this->hostname;
    }

    public function getCmd() {
        return $this->cmd;
    }

    public function getResponse() {
        return $this->response;
    }

    public function getReturnCode() {
        return $this->returnCode;
    }

    public function getLogger() {
        return $this->logger;
    }

    public function setHostname($hostname) {
        $this->hostname = $hostname;
        return $this;
    }

    public function setCmd($cmd) {
        $this->cmd = $cmd;
        return $this;
    }

    public function setResponse($response) {
        $this->response = $response;
        return $this;
    }

    public function setReturnCode($returnCode) {
        $this->returnCode = $returnCode;
        return $this;
    }

    public function setLogger($logger) {
        $this->logger = $logger;
        return $this;
    }

    public function run() {
        $this->response = shell_exec($this->cmd);
    }

}
