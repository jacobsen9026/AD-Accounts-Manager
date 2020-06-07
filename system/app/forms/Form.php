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
use System\App\Session;
use system\Encryption;

class Form
{

    /**
     *
     * @var String
     */
    public static $csrfToken;
    protected $method = "post";
    protected $action;
    protected $name;
    protected $style;
    protected $id;


    /* A 2D array that represents the layout of the form */
    protected $classes = '';
    protected $rowsOfElements;
    /** @var array * */
    protected $currentRow;
    protected $logger;
    /**
     *
     * @var string
     */
    protected $onSubmit;
    /**
     * @var string
     */
    protected $elementModals;

    /**
     * This essentially builds the form tag
     *
     * @param string $action The action attribute of the form tag. If left blank will be current page URL
     * @param string $name   The name attribute of the form tag
     * @param string $method
     */
    public function __construct($action = null, $name = '', $method = 'post')
    {

        /*
         * Initialize form logger
         */


        $this->logger = AppLogger::get();
        /*
         * Check if action is null and if so set to current URL
         */
        if (is_null($action)) {
            $action = $_SERVER["REQUEST_URI"];
        }
        $this->action = $action;
        $this->method = $method;
        $this->name = $name;
        $this->id = $name . 'Form';
// Initialize form row to 0
        $this->currentRow = 0;
        self::$csrfToken = Encryption::encrypt(Session::getID());
    }

    public static function getCsrfToken(): string
    {
        if (self::$csrfToken == null) {
            new self();
        }
        return self::$csrfToken;
    }

    public function setStyle($style)
    {
        $this->style = $style;
        return $this;
    }

    public function addClass(string $addClass)
    {
        $this->logger->info("Adding class to form. Class: $addClass");
        $this->classes .= trim($addClass) . ' ';
        return $this;
    }

    /**
     *
     * @param FormElement $element
     *
     * @return $this
     */
    public function addElementToNewRow(FormElement $element): self
    {

        $this->currentRow++;
        $this->addElementToCurrentRow($element);

        return $this;
    }

    /**
     *
     * @param FormElement $element
     *
     * @return $this
     */
    public function addElementToCurrentRow(FormElement $element)
    {

        $this->rowsOfElements[$this->currentRow][] = $element;

        return $this;
        $this->logger->debug($this->rowsOfElements);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        return $this->id = $id;
    }

    /**
     * Changes the form form a Post/Get request to a client uri call
     *
     * The element provided as the action variable will have it's value
     * appended to the forms action URL
     * (eg: /formSubmitAction/submit[/actionValue])
     *
     * @param FormElement $element
     */
    public function setActionVariable(FormElement $element): void
    {
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
     * Generate the HTML output for the form
     * This is the final step
     *
     * @return string
     */
    public function print()
    {

        $html = "<form action='$this->action' method='$this->method' name='$this->name' id='$this->id' style='$this->style' class='" . $this->getClasses() . "' onclick='$this->onSubmit' enctype='multipart/form-data'>";
        $html .= '<input type="hidden" name="csrfToken" value="' . self::$csrfToken . '"/>';
        foreach ($this->rowsOfElements as $rowOfElements) {

            $html .= '<div class="row">';
            if (is_array($rowOfElements)) {
                $html .= $this->printArrayOfElements($rowOfElements);
            } else {

                $html .= $this->printElement($rowOfElements);
            }

            $html .= '</div>';
        }

        $html .= "</form>";
        $html .= $this->elementModals;
        return $html;
    }

    protected function getClasses()
    {
        $this->logger->info("Getting classes: " . $this->classes);
        return $this->classes;
    }

    /**
     * Returns the output of an array of elements
     *
     * Useful for printing rows at a time
     *
     * @param type $arrayOfElements
     * @param type $html
     *
     * @return type
     */
    protected function printArrayOfElements($arrayOfElements, $html = '')
    {
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

    protected function printElement(FormElement $element)
    {

        $html = $element->print(true);
        $this->elementModals .= $element->printModals() . "\n";

        return $html;
    }

    public function getOnSubmit(): string
    {
        return $this->onSubmit;
    }

    public function setOnSubmit(string $onSubmit)
    {
        $this->onSubmit = $onSubmit;
        return $this;
    }

}
