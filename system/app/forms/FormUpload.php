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
 * Description of FormUpload
 *
 * @author cjacobsen
 */
class FormUpload extends FormElement implements FormElementInterface {
    public function __construct($label = '', $subLabel = '', $name = '', $value = '')
    {
        parent::__construct($label, $subLabel, $name, $value);
        $showFileNameScript = '// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
    console.log("a file upload was prepared");
  var fileName = $(this).val().split("\\\\").pop();
  fileName= fileName.substring(0,30);
  console.log("Filename: "+fileName);
  $(this).siblings(".custom-file-label").addClass("selected text-left").html(fileName);
});';
        $this->setScript($showFileNameScript);
    }


    public function getElementHTML() {
        $html = ' <div class="custom-file ' . $this->getElementClasses() . '">
            <input type="file" class="custom-file-input ' . $this->getInputClasses() . '" id="' . $this->getId() . '" name="' . $this->getName() . '">
            <label class="custom-file-label ' . $this->getLabelClasses() . '" for="customFile">' . $this->getLabel() . '</label>
          </div>';

        $test = 'document.getElementById("selectedFile").click();';

        $html =  ' <div class="system-custom-file' . $this->getElementClasses() . '">
            <input type="file" class="system-custom-file-input ' . $this->getInputClasses() . '" id="' . $this->getId() . '" name="' . $this->getName() . '">
            <label class="system-custom-file-label ' . $this->getLabelClasses() . '" for="'.$this->getId().'">' . $this->getLabel() . '</label>
            ';
            $browseButton = new FormButton('Browse','full');
            $browseButton->setType("button");
            $triggerBrowseFunction = '$("#'.$this->getId().'").click();';
            $onClick = Javascript::onClick($browseButton->getId(), $triggerBrowseFunction);

            $browseButton->setScript($onClick);
//test
            $html.= $browseButton->getElementHTML().'</div>';



        return $html;
    }

}
