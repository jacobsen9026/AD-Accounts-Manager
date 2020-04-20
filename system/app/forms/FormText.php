<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
        $this->setLabel($label);
        $this->setSubLabel($subLabel);
        $this->setName($name);
        $this->value = $value;
    }

    public function autoCompleteUsername() {
        $this->autocomplete = true;
        $script = ' $(function () {
        var getData = function (request, response) {
            $.getJSON(
                "/api/ldap/autocompleteStudent/" + request.term,
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
                "/api/ldap/autocompleteStudentGroup/" + request.term,
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
    public function password() {
        $this->type = "password";
        return $this;
    }

    public function setPlaceholder($placeholder) {
        $this->placeholder = $placeholder;
        return $this;
    }

}
