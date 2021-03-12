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

use App\Models\View\Javascript;

/**
 * Description of FormInput
 *
 * @author cjacobsen
 */
class FormText extends FormElement implements FormElementInterface
{

    //put your code here
    protected $type = "text";
    protected $placeholder = '';
    protected $value;
    protected $disabled = false;
    /**
     * @var bool|mixed
     */
    protected $autofocus = false;

    public function __construct($label = '', $subLabel = '', $name = '', $value = '')
    {
        parent::__construct($label, $subLabel, $name, $value);
        $this->addElementClasses('form-element-text');
    }


    public function getAutoFocus()
    {
        return $this->autofocus;
    }

    public function autofocus($value = true)
    {
        $this->autofocus = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getElementHTML()
    {
        $this->addInputClasses('form-control text-center ui-autocomplete-input');
        $html = '';
        $disable = '';
        if ($this->disabled) {
            $disable = 'disabled';
        }
        $autofocus = "";

        if ($this->autofocus) {
            $autofocus = "autofocus";
        }

        $html .= '<div class="col px-0 "><div class="ui-widget">'
            . '<div class="pr-0 w-100 row mx-0">'
            . '<div class="d-inline-block col mx-auto px-0">'
            . '<input type="' . $this->type . '" class="' . $this->getinputClasses() . '" name="' . $this->getName() . '" id="' . $this->getId() . '" value="' . $this->value . '" placeholder="' . $this->placeholder . '" ' . $disable . ' ' . $autofocus . '>'
            . '</div></div>';
        $html .= '</div></div>';
        return $html;
    }

    public function getId()
    {
        if ($this->id === null || $this->id == '') {
            return $this->getName() . "Input";
        } else {
            return $this->id;
        }
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     *
     * @param boolean $disable
     *
     * @return $this
     */
    public function disable($disable = true)
    {
        $this->disabled = $disable;
        return $this;
    }

    /**
     * Sets the input type to "password"
     */
    public function isPassword()
    {
        $this->type = "password";
        return $this;
    }

    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }


}
