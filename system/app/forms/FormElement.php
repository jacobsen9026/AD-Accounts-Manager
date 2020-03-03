<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app\forms;

/**
 * Description of FormElement
 *
 * @author cjacobsen
 */
class FormElement {

    private $size = "medium";
    private $hidden = false;
    private $label;
    private $subLabel;

    public function setLabel($label) {
        $this->label = $label;
    }

    public function setSubLabel($subLabel) {
        $this->subLabel = $subLabel;
    }

    public function small() {
        $this->size = "small";
    }

    public function medium() {

        $this->size = "medium";
    }

    public function large() {

        $this->size = "large";
    }

    public function hidden() {
        $this->hidden = true;
    }

    public function visible() {
        $this->hidden = false;
    }

    public function getSize() {
        return $this->size;
    }

    public function getHidden() {
        return $this->hidden;
    }

    public function getLabel() {
        return $this->label;
    }

    public function getSubLabel() {
        return $this->subLabel;
    }

    public function setSize($size) {
        $this->size = $size;
    }

}
