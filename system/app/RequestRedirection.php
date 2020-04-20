<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app;

/**
 *
 * @author cjacobsen
 */
trait RequestRedirection {

    //put your code here

    public function redirect($url) {
        $app = App::get();
        if ($app->inDebugMode()) {
            $app->appOutput->appendBody("In Debug Mode<br/>Would have redirected<br/>"
                    . "<a href='" . $url . "'>here</a>");
        } else {
            header('Location: ' . $url);
        }
    }

}
