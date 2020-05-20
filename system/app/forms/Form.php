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
 * Form
 *
 * Form Builder Class
 *
 * Create a new object and add each item via the build methods
 * followed by an addTo call to place it in the form matrix
 *
 *
 * @author cjacobsen
 */
use app\database\Schema;
use App\Models\District\ActiveDirectory;
use App\Models\District\GoogleApps;
use System\App\AppLogger;

class Form {

    private $method = "post";
    private $action;
    private $name;
    private $style;
    private $id;




    /* A 2D array that represents the layout of the form */
    private $rowsOfElements;
//put your code here

    /**
     *
     * @var type
     * @deprecated since version number
     */
    private $formHTML;

    /** @var array * */
    private $inputGroups;
    private $lastComponentBuilt;
    private $lastID;
    private $currentRow;
    private $componentEnd = '</div>';
    private $subForm = false;
    private $logger;

    /**
     *
     * @var String
     */
    public static $csrfToken;

    /**
     *
     * @var string
     */
    private $onSubmit;

    /**
     * This essentially builds the form tag
     *
     * @param string $action The action attribute of the form tag. If left blank will be current page URL
     * @param string $name The name attribute of the form tag
     * @param string $method
     */
    function __construct($action = null, $name = '', $method = 'post') {

// Initialize form logger
        $this->logger = AppLogger::get();
// Check if action is null and if so set to current URL
        if (is_null($action)) {
            $action = $_SERVER["REQUEST_URI"];
        }
        $this->action = $action;
        $this->method = $method;
        $this->name = $name;
        $this->id = $name . 'Form';
// Build form opening tag from construct parameters
        $this->formHTML = '<form class="pb-3" name="' . $this->name . '" method="' . $this->method . '" action="' . $this->action . '" enctype="multipart/form-data">';
// Initialize form row to 0
        $this->currentRow = 0;
        self::$csrfToken = \system\Encryption::encrypt(\System\App\Session::getID());
    }

    public function setStyle($style) {
        $this->style = $style;
        return $this;
    }

    /**
     *
     * @param \System\App\Forms\FormElement $element
     * @return $this
     */
    public function addElementToCurrentRow(FormElement $element) {

        $this->rowsOfElements[$this->currentRow][] = $element;

        return $this;
        $this->logger->debug($this->rowsOfElements);
    }

    /**
     *
     * @param \System\App\Forms\FormElement $element
     * @return $this
     */
    public function addElementToNewRow(FormElement $element) {

        $this->currentRow++;
        $this->addElementToCurrentRow($element);

        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        return $this->id = $id;
    }

    public function setActionVariable(FormElement $element) {
        //var_dump($element);
        $elementName = $element->getName();
        $attribute = 'name="' . $elementName . '"';
        $jsFunction = ' new function(){
    var inputVal = $("#' . $element->getId() . '").val();

console.log(inputVal);
    var urlLink = "' . $this->action . '/";
    urlLink = urlLink + inputVal;



    $("#' . $this->id . '").attr("action", urlLink);
    }';
        $this->setOnSubmit($jsFunction);
    }

    /**
     * Returns the output of an array of elements
     *
     * Useful for printing rows at a time
     *
     * @param type $arrayOfElements
     * @param type $html
     * @return type
     */
    private function printArrayOfElements($arrayOfElements, $html = '') {
        //var_dump($arrayOfElements);

        foreach ($arrayOfElements as $element) {

            if (is_array($element)) {


                $this->printArrayOfElements($element, $html);
            } else {

                $html .= $this->printElement($element);
            }
        }

        return $html;
    }

    private function printElement(FormElement $element) {

        $html = $element->print();

        return $html;
    }

    public static function getCsrfToken(): String {
        if (self::$csrfToken == null) {
            new self();
        }
        return self::$csrfToken;
    }

    /**
     * Generate the HTML output for the form
     * This is the final step
     * @return string
     */
    public function print() {

        $html = "<form action='$this->action' method='$this->method' name='$this->name' id='$this->id' style='$this->style' onclick='$this->onSubmit' enctype='multipart/form-data'>";
        $html .= '<input type="hidden" name="csrfToken" value="' . self::$csrfToken . '"/>';
        foreach ($this->rowsOfElements as $rowOfElements) {

            $html .= '<div class="row">';
            if (is_array($rowOfElements)) {
                $html .= $this->printArrayOfElements($rowOfElements);
            } else {

                $html .= $this->printElement($element);
            }

            $html .= '</div>';
        }

        $html .= "</form>";
        return $html;
    }

    public function getOnSubmit(): string {
        return $this->onSubmit;
    }

    public function setOnSubmit(string $onSubmit) {
        $this->onSubmit = $onSubmit;
        return $this;
    }

}
