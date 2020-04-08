<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app\forms;

/**
 * Description of FormDropdownOption
 *
 * @author cjacobsen
 */
class FormDropdownOption extends FormElement {

    //put your code here
    private $value;
    private $selected = '';

    function __construct($label = null, $value = null) {
        $this->setLabel($label);
        $this->value = $value;
    }

    function getName() {
        return $this->name;
    }

    function getValue() {
        return $this->value;
    }

    function setName($name): void {
        $this->name = $name;
    }

    function setValue($value): void {
        $this->value = $value;
    }

    public function selected() {
        $this->selected = 'selected';
    }

    public function getSelected() {
        return $this->selected;
    }

}
