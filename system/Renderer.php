<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jacobsen\system;

/**
 * Description of Renderer
 *
 * @author cjacobsen
 */
class Renderer {

    //put your code here
    public $output;

    public function renderView(Core $app) {
        echo $this->getHeader();
        echo $app->output;
        echo $this->getFooter();
        if ($app->debugLog) {
            echo "<br/><br/>Debug:<br/>";
            var_dump($app->debugLog);
        }
        //var_export($app);
        //return  Success: ".var_export($app->request->get(),true);
        //$app->request->get;
    }

    public function getHeader() {

    }

    public function getFooter() {

    }

}

?>