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
 * Description of FormRadio
 *
 * @author cjacobsen
 */
class FormRadio extends FormElement implements FormElementInterface
{

    /**
     *
     * @var array<FormRadioOption>
     */
    private $options;

    /**
     *
     * @param string $displayName
     * @param string $value
     * @param bool $selected
     * @param bool $enabled
     *
     * @return FormRadio
     */
    public function addOption($displayName, $value, $selected = false, $enabled = true)
    {
        $id = $this->getId();
        if ($id == null or $id = '') {
            $id = str_replace(" ", '_', $this->getLabel());
        }
        $newOption = new FormRadioOption($this->getName(), $displayName, $value, boolval($selected), $id);
        if (!$enabled) {
            $newOption->disable();
        }
        $this->options[] = $newOption;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function getElementHTML()
    {

        $output = '<div class="row">';
        foreach ($this->options as $option) {
            $output .= $option->getElementHTML();
        }
        $output .= '</div>';
        return $output;
    }

}
