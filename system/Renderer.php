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
    }

    public function draw() {

        $this->logger->info("Drawing of app started");
        echo $this->core->output;
        $this->logger->info("Drawing of app finished");
        if (defined('DEBUG_MODE') and boolval(DEBUG_MODE) and ($this->core->logger != null)) {
            echo $this->view('layouts/system_debug');
        }
    }

}

?>