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
 * Description of FormTextArea
 *
 * @author cjacobsen
 */
class FormTextArea extends FormElement implements FormElementInterface
{

    protected $resizable = true;
    protected $placeholder;
    protected $value;


    public function __construct($label = '', $subLabel = '', $name = '', $value = '')
    {
        parent::__construct($label, $subLabel, $name, $value);
        $this->addElementClasses('form-element-textarea');
    }

    public function getResizable()
    {
        return $this->resizable;
    }

    public function resizable($resizable = true)
    {
        $this->resizable = $resizable;
        return $this;
    }

    public function getElementHTML()
    {
        $output = '<div class="row ' . $this->getElementClasses() . '">';
        if ($this->getLabel() != '') {
            $output .= '<div class = "col-md-4">
        <label class = "' . $this->getLabelClasses() . '" for = "' . $this->getName() . '">
        ' . $this->getLabel() . '
        <small id = "' . $this->getName() . 'HelpBlock" class = "' . $this->getSubLabelClasses() . '">
        ' . $this->getSubLabel() . '
        </small>
        </label>
        </div>';

            $output .= '<div class = "col-md-8">';
        } else {

            $output .= '<div class = "col-md">';
        }
        $output .= '<div class = "row p-3 h-100 ui-widget">
        <textarea type = "text" class = "form-control ' . $this->getInputClasses() . '" name = "' . $this->getName() . '" id="' . $this->getId() . '" placeholder = "' . $this->getPlaceholder() . '">
' . $this->getValue() . '
</textarea>
        </div>
        </div>
        </div>';
        return $output;
    }

    public function getId()
    {

        if ($this->id == null) {
            return htmlspecialchars(str_replace(["<", ">", "=", "-", "/", " ", '"', "'", '.'], "", $this->getName() . 'InputArea'));

        } else {
            return $this->id;
        }
    }

    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

}
