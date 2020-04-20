<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app\forms;

/**
 * Description of FormTextArea
 *
 * @author cjacobsen
 */
class FormTextArea extends FormElement {

    private $resizable = true;
    private $value;

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

    public function getHTML() {

        return '<div class = "row">
        <div class = "col-md-4">
        <label class = "' . $this->getLabelClasses() . '" for = "App_Protected_Admin_Usernames">
        ' . $this->getLabel() . '
        <small id = "App_Protected_Admin_UsernamesHelpBlock" class = "' . $this->getSubLabelClasses() . '">
        ' . $this->getSubLabel() . '
        </small>
        </label>
        </div>
        <div class = "col-md-8">
        <div class = "row p-3 h-100 ui-widget">
        <textarea type = "text" class = "h-100 form-control" name = "' . $this->getName() . '" placeholder = "Enter one username per line">
' . $this->getValue() . '
</textarea>
        </div>
        </div>
        </div>';
    }

}
