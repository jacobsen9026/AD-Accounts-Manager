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
    private $type = "text";
    private $placeholder = '';
    private $value;

    function __construct($label = '', $subLabel = '', $name = '',$value = '') {
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
                "/api/ldap/autocompleteGroup/" + request.term,
                function (data) {
                    response(data);
                });
        };

        var selectItem = function (event, ui) {
        console.log("select");
        console.log(ui);
        console.log(event);

            $("#'.$this->name.'").val(ui.item.value);
            return false;
        }

        $("#'.$this->name.'").autocomplete({
            source: getGroup,
            select: selectItem,
            minLength: 2,
            change: function() {
                $("#'.$this->name.'").css("display", 2);
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

    public function getHTML() {
        
        switch ($this->getSize()) {
            case "large":


                break;
            case "medium":
                $classes = '<div class="col-md"><div class="nav justify-content-center nav-pills m-3">';
                break;
            case "small":
                $classes = '<div class="col-sm"><div class="nav justify-content-center nav-pills m-1">';
                break;

            default:
                break;
        }
        //var_dump($this->isHidden());
        $html = '<div class="col-md"';
        if($this->isHidden()){
            $html .= ' style="display:none" ';
        }
               $html .= '>'
                . '<label class="'.$this->getLabelClasses().'" for="' . $this->name . '">' . $this->getLabel() . '</label>'
                . '<small id="' . $this->name . 'HelpBlock" class="'.$this->getSubLabelClasses().'">' . $this->getSubLabel() . '</small>'
                . '<div class="row p-3 ui-widget">'
                . '<div class="px-0 col-auto ml-auto mr-auto">';
        if ($this->appendIcon != null) {
            $html .= '<div class="d-inline h-100"><span class="input-group-text h-100 text-center d-inline">' . $this->appendIcon . '</span></div>';
        }
       
        $html .= '<div class=" d-inline-block w-80"><input type="'.$this->type.'" class="form-control text-center  w-100 ui-autocomplete-input"  !important;" name="' . $this->name . '" id="' . $this->name . '" value="'.$this->value.'" placeholder="'.$this->placeholder.'"></div>
            </div>';
        $html.=$this->printScript();
        $html .= '</div></div>';
        return $html;
    }
    
    public function setValue($value){
        $this->value = $value;
    }
    
    function getAutocomplete() {
        return $this->autocomplete;
    }

    function getAppendIcon() {
        return $this->appendIcon;
    }

    function getName() {
        return $this->name;
    }

    function setAutocomplete($autocomplete):self {
        $this->autocomplete = $autocomplete;
        return $this;
    }

    function setAppendIcon($appendIcon):self {
        $this->appendIcon = $appendIcon;
        return $this;
    }

    function setName($name):self {
        if (is_array($name)){
            if(key_exists("name", $name)){
                $this->name=$name["name"];
            }
        }else{
        $this->name = $name;
        }
        return $this;
    }
    
    public function isPassword(){
        $this->type="password";
    }
    public function setPlaceholder($placeholder){
        $this->placeholder = $placeholder;
    }



}
