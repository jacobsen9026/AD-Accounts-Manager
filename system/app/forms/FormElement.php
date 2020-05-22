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

namespace System\App\Forms;

/**
 * Description of FormElement
 *
 * @author cjacobsen
 */

use App\Models\View\Javascript;
use System\App\AppLogger;
use System\Models\Ajax\AJAXRequest;
use App\Models\View\Modal;

class FormElement
{
    /**
     * @var string
     */
    private $size = "";
    /**
     * @var bool
     */
    private $hidden = false;
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $label;
    /**
     * @var string
     */
    private $elementClasses = '';
    /**
     * @var string
     */
    private $labelClasses = "font-weight-bold mb-0";
    /**
     * @var string
     */
    private $subLabelClasses = "form-text text-muted mt-0";
    /**
     * @var string
     */
    private $inputClasses = "";
    /**
     * @var
     */
    private $subLabel;
    /**
     * @var string
     */
    private $script = '';
    /**
     * @var
     */
    private $name;
    /**
     * @var bool
     */
    private $hideLabels = false;
    /**
     * @var bool
     */
    private $disabled = false;
    /**
     * @var
     */
    private $tooltip;
    /**
     * @var
     */
    private $value;

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
     * @var AppLogger
     */
    protected $logger;

    /**
     * FormElement constructor.
     *
     * @param string $label
     * @param string $subLabel
     * @param string $name
     * @param string $value
     */
    public function __construct($label = '', $subLabel = '', $name = '', $value = '')
    {

        $this->setLabel($label)
            ->setSubLabel($subLabel)
            ->setName($name)
            ->setValue($value);
        $this->logger = AppLogger::get();
    }

    public function addElementClass(string $addClass)
    {
        $this->elementClasses .= trim($addClass) . ' ';
        return $this;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = (string)$value;
        return $this;
    }

    /**
     * @param $label
     *
     * @return $this
     */
    public function setLabel($label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param $subLabel
     *
     * @return $this
     */
    public function setSubLabel($subLabel): self
    {
        $this->subLabel = $subLabel;

        return $this;
    }

    /**
     * @return type
     */
    public function getBreakpoint()
    {
        return $this->breakpoint;
    }

    /**
     * Manually override breakpoints
     *
     * @param $breakpoint
     *
     * @return $this
     */
    public function setBreakpoint($breakpoint): self
    {
        $this->breakpoint = $breakpoint;
        return $this;
    }

    /**
     *
     * @return Modal
     */
    public function getModal(): ?Modal
    {
        return $this->modal;
    }

    /**
     * @param Modal $modal
     *
     * @return $this
     */
    public function setModal(Modal $modal): self
    {
        $this->setType("button");
        $this->modal = $modal;
        return $this;
    }

    /**
     * @return $this
     */
    public function disable()
    {
        $this->disabled = true;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * Causes the resulting form element to have
     * no col class, thereby be only as wide as it's contents.
     *
     * Other classes may be used to defined layout further via addElementClass();
     *
     * @return $this
     */
    public function tiny(): self
    {
        $this->size = "tiny";
        return $this;
    }

    /**
     *
     * @return $this
     */
    public function small(): self
    {
        $this->size = "small";
        return $this;
    }

    /**
     * @return $this
     */
    public function medium(): self
    {

        $this->size = "medium";

        return $this;
    }

    /**
     * @return $this
     */
    public function large(): self
    {

        $this->size = "large";
        return $this;
    }

    /**
     * @return $this
     */
    public function full(): self
    {
        $this->size = "full";
        return $this;
    }

    /**
     * @return $this
     */
    public function hidden(): self
    {
        $this->hidden = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function visible(): self
    {
        $this->hidden = false;
        return $this;
    }

    /**
     * @return string
     */
    public function getInputClasses(): string
    {
        return $this->inputClasses;
    }

    /**
     * @param $inputClasses
     *
     * @return $this
     */
    public function setInputClasses($inputClasses): self
    {
        $this->inputClasses = $inputClasses;
        return $this;
    }

    /**
     *
     * @param string|array $inputClasses
     *
     * @return $this
     */
    public function addInputClasses($inputClasses): self
    {
        if (is_string($inputClasses)) {
            $this->inputClasses = trim(str_replace("  ", " ", $this->inputClasses)) . ' ' . trim($inputClasses);
        } elseif (is_array($inputClasses)) {
            foreach ($inputClasses as $class) {
                $this->inputClasses = trim(str_replace("  ", " ", $this->inputClasses)) . ' ' . trim($class);
            }
        }
        return $this;
    }

    /**
     * @param $inputClasses
     *
     * @return $this
     */
    public function removeInputClasses($inputClasses): self
    {
        $this->inputClasses = str_replace($inputClasses, '', $this->getInputClasses());
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        if (null === $this->id) {
            return str_replace(" ", "", $this->getName());
        }
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getElementClasses(): string
    {
        return $this->elementClasses;
    }

    /**
     * @return string
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getSubLabel()
    {
        return $this->subLabel;
    }

    /**
     * @return mixed
     */
    public function getTooltip()
    {
        return $this->tooltip;
    }

    /**
     * @param string $contents
     *
     * @return $this
     */
    public function setTooltip(string $contents): self
    {
        $this->tooltip = $contents;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        if (is_array($name)) {
            if (key_exists("name", $name)) {
                $this->name = $name["name"];
            }
        } else {
            $this->name = $name;
        }
        return $this;
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function hideLabels($value = true): self
    {
        $this->hideLabels = $value;
        return $this;
    }

    /**
     * @param $size
     *
     * @return $this
     * @todo document/rework
     *
     */
    public function setSize($size): self
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Appends to the existing script
     *
     * @param $script
     *
     * @return $this
     */
    public function setScript($script): self
    {
        // $this->logger->debug("Script set for " . $this->getName() . ': ' . $script);
        $this->script .= $script;
        return $this;
    }

    /**
     * @return string
     */
    public function getScript(): string
    {
        return $this->script;
    }

    /**
     * @return string
     */
    public function getLabelClasses(): string
    {
        return $this->labelClasses;
    }

    /**
     * @return string
     */
    public function getSubLabelClasses(): string
    {
        return $this->subLabelClasses;
    }

    /**
     * @param $labelClasses
     *
     * @return $this
     */
    public function setLabelClasses($labelClasses): self
    {
        $this->labelClasses = $labelClasses;
        return $this;
    }

    /**
     * @param $subLabelClasses
     *
     * @return $this
     */
    public function setSubLabelClasses($subLabelClasses): self
    {
        $this->subLabelClasses = $subLabelClasses;
        return $this;
    }

    /**
     * @return string
     */
    public function printScript(): string
    {
        $script = $this->getScript();
        if ($script != '') {
            return '<script>' . $script . '</script>';
        }

        return '';
    }

    public function getColSize()
    {
        return $this->colSize;
    }

    /**
     * @param $colSize
     *
     * @return $this
     */
    public function setColSize($colSize)
    {
        $this->colSize = $colSize;

        return $this;
    }

    protected function preProcess()
    {

        switch ($this->getSize()) {
            case "full":
                $this->colSize = '-12';
                break;
            case "large":
                $this->colSize = '-9';
                break;
            case "medium":
                $this->colSize = "-6";
                break;
            case "small":
                $this->colSize = "-2";
                break;
            case 'auto':
                $this->colSize = "-auto";
                break;
            case null:
                $this->colSize = "-6";
                break;

            case 'tiny':
                $this->colSize = null;
                $this->removeInputClasses("w-100");
                break;

            default:
                //$this->colSize = 0;
                break;
        }
        /**
         * if ($this->ajaxRequest != null) {
         * //var_dump($this->ajaxRequest);
         * $this->script = $function = Javascript::buildAJAXRequest($this->ajaxRequest->getTargetURL(), $this->ajaxRequest->getOutputID(), $this->ajaxRequest->getData(), $this->ajaxRequest->getLoadingHTML(), $this->ajaxRequest->getOutputField());
         * $this->script = Javascript::buildOnClick($this->getId(), $function);
         * }
         *
         *
         */
    }

    /**
     * @return string
     */
    protected function printHeader(): string
    {
        if ($this->breakpoint === null) {
            if ($this->getColSize() < 0) {
                $this->breakpoint = '-md';
            } elseif ($this->getColSize() === 0 and $this->getColSize() !== null) {
                $this->breakpoint = '-sm';
            }
        }
        $col = 'col';
        if ($this->getSize() == "tiny") {
            $col = '';
        }
        $size = $col . $this->breakpoint . $this->getColSize();
        $style = '';
        if ($this->isHidden()) {
            $style = ' style="display:none" ';
        }
        $html = '<div class="form-element ' . $size . ' mx-auto ' . $this->elementClasses . '"' . $style . '>';
        return $html . "";
    }

    /**
     * @return string
     */
    protected function printLabel(): string
    {
        return '<label class="' . $this->getLabelClasses() . '" for="' . $this->getId() . '">' . $this->getLabel() . '</label>';
    }

    /**
     * @return string
     */
    protected function printSubLabel(): string
    {
        return '<small id="' . $this->getId() . 'HelpBlock" class="' . $this->getSubLabelClasses() . ' mh-25">' . $this->getSubLabel() . '</small>';
    }

    /**
     * @return string
     */
    protected function printFooter(): string
    {
        return '</div>';
    }

    /**
     * @return string
     */
    public function printModal(): ?string
    {
        if ($this->modal !== null) {
            $this->logger->info($this->getName() . " has a modal");
            $modal = $this->modal->print();
            return $modal;
        }
        return null;
    }

    /**
     * @return string
     */
    protected function printAJAX()
    {
        if ($this->ajaxRequest !== null) {
            $this->logger->debug("creating ajax for " . $this->getName());
            $ajax = $this->ajaxRequest->print();
            $onclick = Javascript::onClick($this->getId(), $ajax);
            $script = "<script>" . $onclick . "</script>";
            return $script;
        }
    }

    /**
     * Returns the HTML for this form element
     *
     * @return string
     */
    public function print(): string
    {
        $this->preProcess();
        $html = $this->printHeader() . "\n";

        if ($this->hideLabels === false && $this instanceof FormTextArea === false) {
            $html .= $this->printLabel() . "\n";
            $html .= $this->printSubLabel() . "\n";
        }
        $html .= $this->getElementHTML() . "\n";
        $html .= $this->printScript() . "\n";
        $html .= $this->printAJAX() . "\n";
        $html .= $this->printFooter() . "\n";
        $html .= $this->printModal();
        return $html . "\n";
    }

}
