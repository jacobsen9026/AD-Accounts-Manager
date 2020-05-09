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
 * Description of FormInput
 *
 * @author cjacobsen
 */
class FormText extends FormElement implements FormElementInterface {

    //put your code here
    private $autocomplete = false;
    private $appendIcon;
    private $type = "text";
    private $placeholder = '';
    private $value;
    private $disabled = false;

    /**
     *
     * @param type $label
     * @param type $subLabel
     * @param type $name
     * @param type $value
     */
    function __construct($label = '', $subLabel = '', $name = '', $value = '') {
        parent::__construct($label, $subLabel, $name);
        $this->value = $value;
    }

    public function autoCompleteStudentUsername() {
        $this->autocomplete = true;
        $script = ' $(function () {
        var getData = function (request, response) {
            $.getJSON("/api/district/autocompleteStudent/" + request.term,
                function (data) {
                    response(data);
                });
        };

        var selectItem = function (event, ui) {

            $("#' . $this->getId() . '").val(ui.item.value);
            return false;
        }

        $("#' . $this->getId() . '").autocomplete({
            source: getData,
            select: selectItem,
            minLength: 2,
            change: function() {
                $("#' . $this->getId() . '").css("display", 2);
            }
        });
    });';
        $this->setScript($script);
        return $this;
    }

    public function autoCompleteStaffUsername() {
        $this->autocomplete = true;
        $script = ' $(function () {
        var getData = function (request, response) {
            $.getJSON(
                "/api/district/autocompleteStaff/" + request.term,
                function (data) {
                    response(data);
                });
        };

        var selectItem = function (event, ui) {
        console.log("select");
        console.log(ui);
        console.log(event);

            $("#username").val(ui.item.value);
            return false;
        }

        $("#username").autocomplete({
            source: getData,
            select: selectItem,
            minLength: 2,
            change: function() {
                $("#username").css("display", 2);
            }
        });
    });';
        $this->setScript($script);
        return $this;
    }

    public function autoCompleteGroupName() {
        $this->autocomplete = true;
        $script = ' $(function () {
        var getGroup = function (request, response) {
            $.getJSON(
                "/api/district/autocompleteGroup/" + request.term,
                function (data) {
                    response(data);
                });
        };

        var selectItem = function (event, ui) {
        console.log("select");
        console.log(ui);
        console.log(event);

            $("#' . $this->getName() . '").val(ui.item.value);
            return false;
        }

        $("#' . $this->getName() . '").autocomplete({
            source: getGroup,
            select: selectItem,
            minLength: 1,
            change: function() {
                $("#' . $this->getName() . '").css("display", 2);
            }
        });
    });';
        $this->setScript($script);
        return $this;
    }

    public function autoCompleteDomainGroupName() {
        $this->autocomplete = true;
        $script = ' $(function () {
        var getGroup = function (request, response) {
            $.getJSON(
                "/api/district/autocompleteDomainGroup/" + request.term,
                function (data) {
                    response(data);
                });
        };

        var selectItem = function (event, ui) {
        console.log("select");
        console.log(ui);
        console.log(event);

            $("#' . $this->getName() . '").val(ui.item.value);
            return false;
        }

        $("#' . $this->getName() . '").autocomplete({
            source: getGroup,
            select: selectItem,
            minLength: 1,
            change: function() {
                $("#' . $this->getName() . '").css("display", 2);
            }
        });
    });';
        $this->setScript($script);
        return $this;
    }

    public function appendIcon($iconHTML) {
        $this->appendIcon = $iconHTML;
        return $this;
    }

    /**
     *
     * @param type $value
     */
    public function getElementHTML() {
        $html = '';
        $disable = '';
        if ($this->disabled) {
            $disable = 'disabled';
        }

        $html .= '<div class="col"><div class="row pl-3 ui-widget">'
                . '<div class="pr-0 w-100 row">';
        if ($this->appendIcon != null) {
            $inputCol = 10;
            $html .= '<span class="d-inline-block col-2 pr-0 mr-0 input-group-text h-100 text-center d-inline">' . $this->appendIcon . '</span>'
            ;
        }

        $html .= '<div class="d-inline-block col mx-auto pr-0">'
                . '<input type="' . $this->type . '" class="form-control text-center ui-autocomplete-input" name="' . $this->getName() . '" id="' . $this->getName() . '" value="' . $this->value . '" placeholder="' . $this->placeholder . '" ' . $disable . '>'
                . '</div></div>';
        $html .= '</div></div>';
        return $html;
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    function getAutocomplete() {
        return $this->autocomplete;
    }

    function getAppendIcon() {
        return $this->appendIcon;
    }

    public function disable($disable = true) {
        $this->disabled = $disable;
        return $this;
    }

    function setAutocomplete($autocomplete): self {
        $this->autocomplete = $autocomplete;
        return $this;
    }

    function setAppendIcon($appendIcon): self {
        $this->appendIcon = $appendIcon;
        return $this;
    }

    /**
     * Sets the input type to "password"
     */
    public function isPassword() {
        $this->type = "password";
        return $this;
    }

    public function setPlaceholder($placeholder) {
        $this->placeholder = $placeholder;
        return $this;
    }

}
