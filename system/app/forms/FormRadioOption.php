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
 * Description of FormRadioOption
 *
 * @author cjacobsen
 */
class FormRadioOption extends FormElement implements FormElementInterface
{

    /**
     *
     * @var string
     */
    protected $value;

    /**
     *
     * @var bool
     */
    protected $selected = false;

    public function __construct($name, $label = '', $value = '', $selected = false, $id = '')
    {
        parent::__construct($label, null, $name);
        $this->setValue($value);
        $this->setSelected($selected);
        $this->setId($id);
    }

    /**
     * @return string
     */
    public function getElementHTML(): string
    {
        $disabled = '';
        if ($this->isDisabled()) {
            $disabled = ' disabled';
        }
        $checked = '';
        if ($this->getSelected()) {
            $checked = ' checked ';
        }
        return '<div class="col-md custom-radio custom-control">'
            . '<input class="custom-control-input" type="radio" name="' . $this->getName() . '" id="' . $this->getId() . '_' . $this->getLabel() . '" value="' . $this->getValue() . '" ' . $checked . ' ' . $disabled . '>'
            . '<label class="custom-control-label" for="' . $this->getId() . '_' . $this->getLabel() . '">' . $this->getLabel() . '</label>'
            . '</div>';
        //$output .=
    }

    public function getSelected(): bool
    {
        return $this->selected;
    }

    /**
     * @param bool $selected
     *
     * @return $this
     */
    public function setSelected(bool $selected): FormRadioOption
    {
        $this->selected = $selected;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

}
