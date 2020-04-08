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
    private $labelClasses = "font-weight-bold mb-0";
    private $subLabelClasses = "form-text text-muted mt-0";
    private $subLabel;
    private $script = '';

    public function setLabel($label) {
        $this->label = $label;

        return $this;
    }

    public function setSubLabel($subLabel) {
        $this->subLabel = $subLabel;

        return $this;
    }

    public function small() {
        $this->size = "small";
        return $this;
    }

    public function medium() {

        $this->size = "medium";

        return $this;
    }

    public function large() {

        $this->size = "large";
        return $this;
    }

    public function hidden() {
        $this->hidden = true;
        return $this;
    }

    public function visible() {
        $this->hidden = false;
        return $this;
    }

    public function getSize() {
        return $this->size;
    }

    public function isHidden() {
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
        return $this;
    }

    public function setScript($script) {
        $this->script = $script;
        return $this;
    }

    public function getScript() {
        return $this->script;
    }

    function getLabelClasses() {
        return $this->labelClasses;
    }

    function getSubLabelClasses() {
        return $this->subLabelClasses;
    }

    function setLabelClasses($labelClasses) {
        $this->labelClasses = $labelClasses;
        return $this;
    }

    function setSubLabelClasses($subLabelClasses) {
        $this->subLabelClasses = $subLabelClasses;
        return $this;
    }

    function printScript() {
        $script = $this->getScript();
        if ($script != '') {
            return '<script>' . $script . '</script>';
        }
        return '';
    }

}
