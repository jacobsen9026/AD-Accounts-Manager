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
    private $id;
    private $label;
    private $labelClasses = "font-weight-bold mb-0";
    private $subLabelClasses = "form-text text-muted mt-0";
    private $subLabel;
    private $script = '';
    private $name;
    private $hideLabels = false;

    /**
     *
     * @var int|string This is the col size number for BootStrap CSS (1-12 or auto)
     */
    private $colSize;

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

    public function full() {
        $this->size = "full";
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

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
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

    function getName() {
        return $this->name;
    }

    function setName($name) {
        if (is_array($name)) {
            if (key_exists("name", $name)) {
                $this->name = $name["name"];
            }
        } else {
            $this->name = $name;
        }
        return $this;
    }

    public function hideLabels($value = true) {
        $this->hideLabels = true;
        return $this;
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

    public function getColSize() {
        return $this->colSize;
    }

    public function setColSize(int $colSize) {
        $this->colSize = $colSize;
        return $this;
    }

    protected function preProcess() {
        switch ($this->getSize()) {
            case "full":
                $this->colSize = 12;
                break;
            case "large":
                $this->colSize = 9;
                break;
            case "medium":
                $this->colSize = 6;
                break;
            case "small":
                $this->colSize = 2;
                break;

            default:
                $this->colSize = "auto";
                break;
        }
    }

    protected function printHeader() {
        $html = '<div class="col-md-' . $this->getColSize() . ' mx-auto"';
        if ($this->isHidden()) {
            $html .= ' style="display:none" ';
        }
        $html .= '>';
        return $html;
    }

    protected function printLabel() {
        return '<label class="' . $this->getLabelClasses() . '" for="' . $this->getName() . '">' . $this->getLabel() . '</label>';
    }

    protected function printSubLabel() {
        return '<small id="' . $this->getName() . 'HelpBlock" class="' . $this->getSubLabelClasses() . '">' . $this->getSubLabel() . '</small>';
    }

    protected function printFooter() {
        return '</div>';
    }

    public function getHTML() {

        $this->preProcess();
        $html = $this->printHeader();
        if ($this->hideLabels == false) {
            $html .= $this->printLabel();
            $html .= $this->printSubLabel();
        }
        $html .= $this->getElementHTML();
        $html .= $this->printScript();
        $html .= $this->printFooter();
        return $html;
    }

}
