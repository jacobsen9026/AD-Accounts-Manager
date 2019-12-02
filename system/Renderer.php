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
class Renderer {

    //put your code here
    public $output;
    private $core;

    function __construct(Core $core) {
        $this->core = $core;
    }

    public function draw() {
        //var_dump($this->core->output);
        echo $this->core->output;
        $this->checkDebug();

        //var_export($app);
        //return  Success: ".var_export($app->request->get(),true);
        //$app->request->get;
    }

    private function checkDebug() {

        if (defined('DEBUG_MODE') and boolval(DEBUG_MODE) and ($this->core->logger != null)) {

            echo "<br/><br/>System Debug:<br/>";
            var_dump($this->core->logger->getLogs()['debug']);

            echo "<br/><br/>System Warning:<br/>";
            var_dump($this->core->logger->getLogs()['warning']);

            echo "<br/><br/>System Error:<br/>";
            var_dump($this->core->logger->getLogs()['error']);

            echo "<br/><br/>System Infor:<br/>";
            var_dump($this->core->logger->getLogs()['info']);
        }
    }

}

?>