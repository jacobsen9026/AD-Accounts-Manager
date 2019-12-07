<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 *
 * This class is not yet utilized and may never be.
 */

namespace system\app;

/**
 * Description of CoreForm
 *
 * @author cjacobsen
 */
class CoreForm {

    //put your code here

    private $action;
    private $name;
    private $method;
    private $target;
    private $autoComplete;

    function __construct($name, $action, $method = 'post', $target = '_self', $autoComplete = 'on') {
        $this->action = $action;
        $this->name = $name;
        $this->method = $method;
        $this->target = $target;
        $this->autoComplete = $autoComplete;
    }

    public function addInput() {

    }

    public function addTextArea() {

    }

    public function addCheckbox() {

    }

}
