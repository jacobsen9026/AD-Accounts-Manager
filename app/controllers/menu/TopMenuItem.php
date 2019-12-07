<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\menu;

/**
 * Description of TopMenuItem
 *
 * @author cjacobsen
 */
class TopMenuItem {

    //put your code here
    public $displayText;
    public $subItems;

    function __construct($displayText) {
        $this->displayText = $displayText;
    }

    public function addSubItem(SubMenuItem $item) {
        $this->subItems[] = $item;
    }

}
