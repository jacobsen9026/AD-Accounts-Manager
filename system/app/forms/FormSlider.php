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
use System\App\AppLogger;

class FormSlider extends FormRadio
{
    /**
     * FormSlider constructor.
     *
     * @param string $label
     * @param string $subLabel
     * @param string $name
     * @param string $value
     */
    public function __construct($label = '', $subLabel = '', $name = '', $value = '')
    {
        parent::__construct($label, $subLabel, $name, $value);
        $this->addElementClasses('mb-4');
        AppLogger::get()->debug("New form slider with value: " . $value);
    }


    public function getElementHTML()
    {

        if (empty($this->getOptions())) {
            $this->addOption('Off', 0);
            $this->addOption('On', 1);
        }
        $disable = '';
        if ($this->isDisabled()) {
            $disable = ' disabled ';

        }

        /* @var $option FormRadioOption */
        $selectedOption = new FormRadioOption('');
        AppLogger::get()->debug('FormSlider Options....');
        AppLogger::get()->debug($this->getOptions());
        foreach ($this->getOptions() as $option) {
            $cases[] = 'case "' . $option->getValue() . '":' . "\n"
                . 'output = "' . $option->getLabel() . '";'
                . 'break;';

            AppLogger::get()->debug($option->getValue());
            AppLogger::get()->debug($this->getValue());
            if ($option->getValue() == $this->getValue()) {
                AppLogger::get()->info("selected option with value:" . $this->value);
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
        $width = 50 * (count($this->getOptions()) - 1);
        $html = '<input  type="range" class="custom-range h-50 ' . $this->getInputClasses() . '" name="' . $this->getName() . '" style="max-width:' . $width . 'px;" min="0" max="' . (count($this->getOptions()) - 1) . '" id="' . $this->getId() . '" value="' . (int)$this->getValue() . '" ' . $disable . '>';
        $html .= '<div class="text-muted small" id="' . $this->getId() . '_Status_Text">' . $selectedOption->getLabel() . '</div>';
        return $html;
    }

    public function getValue()
    {
        $value = parent::getValue();
        if ($value === null) {
            $value = 0;
        }
        return $value;
    }


}
