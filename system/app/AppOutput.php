<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app;

/**
 * Description of AppOutput
 *
 * @author cjacobsen
 */
class AppOutput {

    //put your code here
    private $body;
    private $logs;

    public function getBody() {
        return $this->body;
    }

    public function getLogs() {
        return $this->logs;
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public function setLogs($logs) {
        $this->logs = $logs;
    }

    public function appendBody($body) {
        $this->body .= $body;
    }

    public function prependBody($body) {
        $this->body = $body . $this->body;
    }

}
