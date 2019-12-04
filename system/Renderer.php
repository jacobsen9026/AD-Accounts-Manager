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
    private $logger;

    function __construct(Core $core) {
        $this->core = $core;
        $this->logger = $core->logger;
        $this->appLogger = $core->appDebugger;
    }

    public function draw() {
//var_dump($this->core->appOutput);
        $this->logger->info("Drawing of app started");
        if (isset($this->core->appOutput) and $this->core->appOutput != '') {
            echo $this->core->appOutput;
        } else {
            echo $this->getNoAppOutputWarning();
        }
        $this->logger->info("Drawing of app finished");
        echo $this->view('layouts/debugToolbar');
    }

    private function getNoAppOutputWarning() {
        $this->include('system/views/noAppOutput');
    }

}
?>