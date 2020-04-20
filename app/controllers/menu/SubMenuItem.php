<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\menu;

/**
 * Description of SubMenuItem
 *
 * @author cjacobsen
 */
class SubMenuItem extends MenuItem {

    //put your code here


    public $data;

    function __construct($displayText, $targetURL = "/", $data = null) {
        $this->setDisplayText($displayText);
        $this->setTargetURL($targetURL);
        $this->data = $data;
    }

}
