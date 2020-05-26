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
 * Description of FormSlider
 *
 * @author cjacobsen
 */

use App\Models\View\Javascript;
use System\App\Forms\FormRadioOption;

class FormSlider extends FormRadio implements FormElementInterface
{
    public function getValue()
    {
        $value = parent::getValue();
        if ($value === null) {
            $value = 0;
        }
        return $value;
    }


    public function getElementHTML()
    {

        /* @var $option FormRadioOption */
        $selectedOption = new FormRadioOption('');
        foreach ($this->getOptions() as $option) {
            $cases[] = 'case "' . $option->getValue() . '":' . "\n"
                . 'output = "' . $option->getLabel() . '";'
                . 'break;';
            if ($option->getSelected()) {
                $selectedOption = $option;
            }
        }
        // $this->setId($this->getId() . hash("sha256", $this->getId()));
        $outputId = $this->getId() . '_Status_Text';
        $function = 'let value = $(this).val();'
            . 'console.log(value);'
            . 'let output = "";'
            . 'switch (value){';
        foreach ($cases as $case) {
            $function .= $case;
        }

        $function .= '}'
            . '$("#' . $outputId . '").html(output);';
        $this->setScript(Javascript::on($this->getId(), $function, 'click touch'));
        $html = '<input  type="range" class="custom-range h-50" name="' . $this->getName() . '" style="max-width:50px;" min="0" max="1" id="' . $this->getId() . '" value="' . $this->getValue() . '">';
        $html .= '<div class="text-muted small" id="' . $this->getId() . '_Status_Text">' . $selectedOption->getLabel() . '</div>';
        return $html;
    }


}
