<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app\forms;

/**
 * Description of FormSelect
 *
 * @author cjacobsen
 */
class FormDropdown extends FormElement {
    //put your code here

    /**
     *
     * @var FormDropdownOption
     */
    private $options;
    private $name;

    /**
     *
     * @param string $label
     * @param string $name
     */
    function __construct($label, $name) {
        $this->setLabel($label);
        $this->name = $name;
    }

    public function createOption($label, $value) {
        $this->options[] = new FormDropdownOption($label, $value);
        return $this;
    }

    public function addOption($option) {
        $this->options[] = $option;
        return $this;
    }

    function getOptions() {
        return $this->options;
    }

    function getHTML() {
        $output = '<div class="col-md">'
                . '<label class="font-weight-bold mb-0" for="' . $this->name . '">' . $this->getLabel() . '</label>'
                . '<br/>'
                . '<select class="col-md-6 col-lg-4 col-xl-2 form-control mx-auto custom-select text-center" name="' . $this->name . '">';

        foreach ($this->options as $option) {
            $output .= '<option value="' . $option->getValue() . '" ' . $option->getSelected() . '>' . $option->getLabel() . '</option>';
        }
        $output .= '</select>'
                . '</div>';
        return $output;
    }

}
