<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app\forms;

/**
 * Description of FormButton
 *
 * @author cjacobsen
 */
class FormButton extends FormElement {

    private $target = '';
    private $theme = "primary";
    private $type = 'submit';

    //put your code here
    /**
     *
     * @param type $label
     * @param type $subLabel
     * @param type $size Must be one of: small,medium,large
     */
    function __construct($label = '', $subLabel = '', $size = "medium") {
        $this->setLabel($label);
        $this->setSubLabel($subLabel);
        $this->setSize($size);
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
    }

    public function getTarget() {
        return $this->target;
    }

    public function setTarget($target) {
        $this->target = $target;
        return $this;
    }

    public function getHTML() {
        switch ($this->getSize()) {
            case "large":


                break;
            case "medium":
                $html = '<div class="col-md"><div class="nav justify-content-center nav-pills m-3">';
                break;
            case "small":
                $html = '<div class="col-sm"><div class="nav justify-content-center nav-pills m-1">';
                break;

            default:
                break;
        }
        $html .= '
        <button type="' . $this->getType() . '" class="nav-link btn btn-' . $this->getTheme() . '">' . $this->getLabel() . '</button>
</div></div>';
        $html .= $this->printScript();
        return $html;
    }

}
