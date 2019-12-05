<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\config;

/**
 * Description of Theme
 *
 * @author cjacobsen
 */
class Theme {

    //put your code here
    const DEFAULT_THEME = "default_theme";
    const BLUE_THEME = "blue_theme";
    const RED_THEME = "red_theme";
    const GREEN_THEME = "green_theme";

    public function getThemes() {
        $rc = new \ReflectionClass($this);
        return $rc->getConstants();
    }

}
