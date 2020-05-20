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
 * Description of FormButton
 *
 * @author cjacobsen
 */
use App\Models\View\Javascript;
use System\Models\Ajax\AJAXRequest;
use App\Models\View\Modal;

class FormButton extends FormElement implements FormElementInterface {

    private $theme = "primary";
    private $type = 'submit';

//put your code here

    /**
     *
     * @param type $label
     * @param type $subLabel
     * @param type $size Must be one of: small,medium,large
     */
    function __construct(string $name, $size = "medium") {
        parent::__construct(null, null, $name);

        $this->setName($name);
        $this->setSize($size);
        $this->setInputClasses("w-100 btn");
    }

    function getTheme() {
        return $this->theme;
    }

    function getType() {
        return $this->type;
    }

    function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setTheme($theme) {
        $this->theme = $theme;
        return $this;
    }

    public function getId() {
        $id = parent::getId();
        if ($id == null) {
            return str_replace(" ", "", $this->getName()) . "Button";
        } else {
            return $id;
        }
    }

    /**
     *
     * @return string
     */
    public function getElementHTML() {

        $textClass = '';
        if ($this->getTheme() == 'warning') {
            $textClass = 'text-light';
        }
        $this->addInputClasses(['btn-' . $this->getTheme(), $textClass]);
        $html = '<div id="' . $this->getId() . '_Button_container" class="' . $this->getElementClasses() . '">
        <button id="' . $this->getId() . '" type="' . $this->getType() . '" class="' . $this->getInputClasses() . '"';
        if ($this->getModal() != null) {

            $html .= ' data-toggle="modal" data-target="#' . $this->getModal()->getId() . '" ';
            //$html .= ' data-toggle="modal" data-target="#exampleModal" ';
        }
        $html .= '>'
                . $this->getName() .
                '</button></div>';
        //var_dump($html);
        return $html;
    }

    public function addModal($modalTitle, $modalBody, $modalTheme) {
        $modal = new Modal();
        $modal->setTitle($modalTitle)
                ->setBody($modalBody)
                ->setID($this->getId() . '_Modal')
                ->setTheme($modalTheme);
        $this->setModal($modal);
        $this->setType("button");
        \System\App\AppLogger::get()->info("Added modal to " . $this->getName());
        return $this;
    }

    /**
     *  Converts the button to a background AJAX call
     * @param string $url The target URL of the AJAX request
     * @param string $outputID The ID of the HTML element to place the response
     * @param mixed $data Can be a prepared array, a Form object, or a JQuery string
     */
    public function addAJAXRequest($url, $outputID = null, $data = null, $showLoading = false, $outputElement = 'html') {

        $this->ajaxRequest = new AJAXRequest($url, $data);
        if ($outputID != null)
            $this->ajaxRequest->setOutputID($outputID);
        if ($outputElement != null)
            $this->ajaxRequest->setOutputElement($outputElement);
        if ($data != null)
            $this->ajaxRequest->setData($data);
        $this->setType('button');



        return $this;
    }

    /**
     * Turns the button into a link that can post data if provided
     * @param type $url
     * @param type $data
     */
    public function addClientRequest($url, $data = null) {
        $this->setType('button');
        $function = Javascript::buildClientRequest($url, $data);
        $script = Javascript::onClick($this->getId(), $function);
        //var_dump($script);
        $this->setScript($script);
        return $this;
    }

}
