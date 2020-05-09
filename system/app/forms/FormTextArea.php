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
 * Description of FormTextArea
 *
 * @author cjacobsen
 */
class FormTextArea extends FormElement implements FormElementInterface {

    private $resizable = true;
    private $placeholder;
    private $value;

    public function getPlaceholder() {
        return $this->placeholder;
    }

    public function setPlaceholder($placeholder) {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function getResizable() {
        return $this->resizable;
    }

    public function getValue() {
        return $this->value;
    }

    public function resizable($resizable = true) {
        $this->resizable = $resizable;
        return $this;
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    public function getElementHTML() {
        $output = '<div class="row">
        <div class = "col-md-4">
        <label class = "' . $this->getLabelClasses() . '" for = "' . $this->getName() . '">
        ' . $this->getLabel() . '
        <small id = "' . $this->getName() . 'HelpBlock" class = "' . $this->getSubLabelClasses() . '">
        ' . $this->getSubLabel() . '
        </small>
        </label>
        </div>
        <div class = "col-md-8">
        <div class = "row p-3 h-100 ui-widget">
        <textarea type = "text" class = "h-100 form-control" name = "' . $this->getName() . '" placeholder = "' . $this->getPlaceholder() . '">
' . $this->getValue() . '
</textarea>
        </div>
        </div>
        </div>';
        return $output;
    }

}
