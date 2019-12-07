<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
    public $core;
    public $logger;
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

        if ($this->appLogger->getLogs()['error'] !== null and sizeof($this->appLogger->getLogs()['error']) > 0) {
            return true;
        }
        if ($this->logger->getLogs()['error'] !== null and sizeof($this->logger->getLogs()['error']) > 0) {
            return true;
        }
        return false;
    }

}

?>