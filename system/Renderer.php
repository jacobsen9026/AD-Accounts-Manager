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

    public function draw(Core $core) {
        echo $core->output;
        if (isset($core->debugLog) and sizeof($core->debugLog) > 0) {
            echo "<br/><br/>System Debug:<br/>";
            var_dump($core->debugLog);
        }

        //var_export($app);
        //return  Success: ".var_export($app->request->get(),true);
        //$app->request->get;
    }

}

?>