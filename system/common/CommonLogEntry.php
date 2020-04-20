<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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

    public function getDeltaTime() {
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

    public function setDeltaTime($deltaTime) {
        $this->elapsedTime = $deltaTime;
        return $this;
    }

    public function setLevel($level) {
        $this->level = $level;
        return $this;
    }

    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    public function setBacktrace($backtrace) {
        $this->backtrace = $backtrace;
        return $this;
    }

}
