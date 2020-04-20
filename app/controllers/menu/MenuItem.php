<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\menu;

/**
 * Description of MenuItem
 *
 * @author cjacobsen
 */
class MenuItem {

    public $displayText;
    public $targetURL;

    public function getDisplayText() {
        return $this->displayText;
    }

    public function getTargetURL() {
        return $this->targetURL;
    }

    public function setDisplayText($displayText) {
        $this->displayText = $displayText;
        return $this;
    }

    public function setTargetURL($targetURL) {
        $this->targetURL = $targetURL;
        return $this;
    }

}
