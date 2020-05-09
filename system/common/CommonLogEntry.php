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

namespace system\common;

/**
 * Description of CommonLogEntry
 *
 * @author cjacobsen
 */
class CommonLogEntry {

    //put your code here
    private $elapsedTime;
    private $level;
    private $message;
    private $backtrace;

    public function __construct(CommonLogger $logger) {
        $this->elapsedTime = floatval(microtime()) - $logger->getStartTime();
    }

    public function getElapsedTime() {
        return $this->elapsedTime;
    }

    public function getLevel() {
        return $this->level;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getBacktrace() {
        return $this->backtrace;
    }

    /**
     *
     * @param type $elapsedTime
     * @return
     */
    public function setElapsedTime($elapsedTime) {
        $this->elapsedTime = $elapsedTime;
        return $this;
    }

    /**
     *
     * @param type $level
     * @return $this
     */
    public function setLevel($level) {
        $this->level = $level;
        return $this;
    }

    /**
     *
     * @param type $message
     * @return $this
     */
    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    /**
     *
     * @param type $backtrace
     * @return $this
     */
    public function setBacktrace($backtrace) {
        $this->backtrace = $backtrace;
        return $this;
    }

}
