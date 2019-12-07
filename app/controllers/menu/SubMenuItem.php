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
class SubMenuItem {

    //put your code here
    public $displayText;
    public $targetURL;
    public $data;

    function __construct($displayText, $targetURL = "/", $data = null) {
        $this->displayText = $displayText;
        $this->targetURL = $targetURL;
        $this->data = $data;
    }

}
