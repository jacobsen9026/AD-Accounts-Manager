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
class TopMenuItem extends MenuItem {

    //put your code here
    public $subItems;

    function __construct($displayText) {
        $this->setDisplayText($displayText);
    }

    public function addSubItem(SubMenuItem $item) {
        $this->subItems[] = $item;
    }

}
