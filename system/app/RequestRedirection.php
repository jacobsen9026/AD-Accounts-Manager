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
        if (isset($this->app)) {
            if ($this->app->inDebugMode()) {
                $this->app->outputBody .= "In Debug Mode<br/>Would have redirected<br/>"
                        . "<a href='" . $url . "'>here</a>";
            } else {
                header('Location: ' . $url);
            }
        } elseif (method_exists($this, 'inDebugMode')) {
            if ($this->inDebugMode()) {
                $this->outputBody .= "In Debug Mode<br/>Would have redirected<br/>"
                        . "<a href='" . $url . "'>here</a>";
            } else {
                header('Location: ' . $url);
            }
        }
    }

}
