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
class FormText extends FormElement {

    //put your code here
    private $autocomplete = false;
    private $appendIcon;
    private $name;

    function __construct($label = '', $subLabel = '', $name = '') {
        $this->setLabel($label);
        $this->setSubLabel($subLabel);
        $this->name = $name;
    }

    public function autoCompleteUsername() {
        $this->autocomplete = true;
        return $this;
    }

    public function appendIcon($iconHTML) {
        $this->appendIcon = $iconHTML;
        return $this;
    }

    public function getHTML() {
        $html = '<div class="col-md"><label class="font-weight-bold mb-0" for="' . $this->name . '">' . $this->getLabel() . '</label>'
                . '<small id="' . $this->name . 'HelpBlock" class="form-text text-muted mt-0">' . $this->getSubLabel() . '</small>'
                . '<div class="row p-3 ui-widget">'
                . '<div class="px-0 col-auto ml-auto mr-auto">';
        if ($this->appendIcon != null) {
            $html .= '<div class="d-inline h-100"><span class="input-group-text h-100 text-center d-inline">' . $this->appendIcon . '</span></div>';
        }
        $html .= '<div class="d-inline-block"><input type="text" class="col form-control text-center  ml-0 mr-auto ui-autocomplete-input" name="' . $this->name . '" id="' . $this->name . '"></div>
            </div>';
        if ($this->autocomplete) {
            $html .= '<script>
        $(function () {
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
    });

    </script>';
        }
        $html .= '</div></div>';
        return $html;
    }

}
