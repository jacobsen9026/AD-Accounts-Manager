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
        if (isset($this->core->debugLog) and sizeof($this->core->debugLog) > 0) {
            echo "<br/><br/>System Debug:<br/>";
            var_dump($this->core->debugLog);
        }

        //var_export($app);
        //return  Success: ".var_export($app->request->get(),true);
        //$app->request->get;
    }

}

?>