<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app\forms;

/**
 * Description of FormHTML
 *
 * @author cjacobsen
 */
class FormHTML extends FormElement {
    //put your code here
    private $html;
    
    function getHtml() {
        return $this->html;
    }

    function setHtml($html): void {
        $this->html = $html;
    }


}
