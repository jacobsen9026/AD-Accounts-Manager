<?php

/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace system\app\forms;

/**
 * Description of FormElement
 *
 * @author cjacobsen
 */
use app\models\view\Javascript;
use system\app\forms\ajax\AJAXRequest;
use app\models\view\Modal;

class FormElement {

    private $size = "";
    private $hidden = false;
    private $id;
    private $label;
    private $labelClasses = "font-weight-bold mb-0";
    private $subLabelClasses = "form-text text-muted mt-0";
    private $subLabel;
    private $script = '';
    private $name;
    private $hideLabels = false;
    private $disabled = false;
    private $tooltip;

    /**
     *
     * @var Modal
     */
    private $modal;

    /**
     *
     * @var AJAXRequest
     */
    protected $ajaxRequest;

    /**
     *
     * @var type The bootstrap breakpoint for the elements column
     */
    private $breakpoint;

    /**
     *
     * @var int|string This is the col size number for BootStrap CSS (1-12 or auto)
     */
    private $colSize;

    /**
     *  Create a form element
     * @param type $label
     * @param type $subLabel
     * @param type $name
     */
    public function __construct($label = '', $subLabel = '', $name = '') {

        $this->setLabel($label)
                ->setSubLabel($subLabel)
                ->setName($name);
    }

    public function setLabel($label) {
        $this->label = $label;

        return $this;
    }

    public function setSubLabel($subLabel) {
        $this->subLabel = $subLabel;

        return $this;
    }

    public function getModal() {
        return $this->modal;
    }

    public function setModal($modal) {

        $this->modal = $modal;
        return $this;
    }

    /**
     *
     * @return $this
     */
    public function disable() {
        $this->disabled = true;
        return $this;
    }

    /**
     *
     * @return type
     */
    public function isDisabled() {
        return $this->disabled;
    }

    /**
     *
     * @return $this
     */
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
        if ($this->id == null) {
            return str_replace(" ", "", $this->getName());
        }
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

    public function getTooltip() {
        return $this->tooltip;
    }

    public function setTooltip($contents) {
        $this->tooltip = $contents;
        return $this;
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
        //\system\app\AppLogger::get()->debug("Script set for " . $this->getName() . ': ' . $script);
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
            case 'auto';

                $this->colSize = "auto";
            default:

                $this->colSize = null;
                break;
        }
        /**
          if ($this->ajaxRequest != null) {
          //var_dump($this->ajaxRequest);
          $this->script = $function = Javascript::buildAJAXRequest($this->ajaxRequest->getTargetURL(), $this->ajaxRequest->getOutputID(), $this->ajaxRequest->getData(), $this->ajaxRequest->getLoadingHTML(), $this->ajaxRequest->getOutputField());
          $this->script = Javascript::buildOnClick($this->getId(), $function);
          }
         *
         *
         */
    }

    protected function printHeader() {
        if ($this->getColSize() > 0) {
            $this->breakpoint = '-md-';
        } else {
            $this->breakpoint = '-sm';
        }
        $html = '<div class="col' . $this->breakpoint . $this->getColSize() . ' mx-auto mb-4"';


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

    public function printModal() {
        if ($this->modal != null) {
            \system\app\AppLogger::get()->info($this->getName() . " has a modal");
            $modal = $this->modal->print();
            return $modal;
        }
    }

    protected function printAJAX() {
        if ($this->ajaxRequest != null) {
            \system\app\AppLogger::get()->debug("creating ajax for " . $this->getName());
            $ajax = $this->ajaxRequest->print();
            $onclick = Javascript::onClick($this->getId(), $ajax);
            $script = "<script>" . $onclick . "</script>";
            //\system\app\AppLogger::get()->debug($script);
            return $script;
        }
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
        $html .= $this->printAJAX();
        $html .= $this->printFooter();
        return $html;
    }

}
