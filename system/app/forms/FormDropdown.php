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
 * Description of FormDropdown
 *
 * @author cjacobsen
 */
class FormDropdown extends FormElement
{
    //put your code here

    /**
     *
     * @var FormDropdownOption
     */
    private $options;
    private $name;

    public function createOption($label, $value)
    {
        $this->options[] = new FormDropdownOption($label, $value);
        return $this;
    }

    public function addOption($option)
    {
        $this->options[] = $option;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    function getElementHTML()
    {
        $disable = '';
        if ($this->isDisabled())
            $disable = ' disabled ';
        $output = '<select class="form-control mx-auto custom-select text-center" name="' . $this->getName() . '" ' . $disable . '>';

        foreach ($this->getOptions() as $option) {
            $output .= '<option value="' . $option->getValue() . '" ' . $option->getSelected() . '>' . $option->getLabel() . '</option>';
        }
        $output .= '</select>';
        return $output;
    }

}
