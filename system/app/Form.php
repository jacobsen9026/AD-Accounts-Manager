<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app;

/**
 * Description of Form
 *
 * @author cjacobsen
 */
use app\database\Schema;

class Form {

//put your code here
    private $formHTML;
    private $inputGroups;
    private $lastComponentBuilt;
    private $currentRow;
    private $formEnd = '</div>';
    private $subForm = false;

    /**
     * __construct
     * Create a form builder object
     *
     * @param string $action
     * @param string $name
     * @param string $method
     */
    function __construct($action, $name = '', $method = 'post') {
        $this->formHTML = '<form name="' . $name . '" method="' . $method . '" action="' . $action . '">';
        $this->currentRow = 1;
    }

    public function subForm() {
        //var_dump($this->formHTML);
        $this->formHTML = '';
        //var_dump($this->formHTML);
        $this->subForm = true;
        return $this;
    }

    public function getFormHTML() {
        $formBody = '';
        //Create display tables for input groups
        //var_dump($this->inputGroups);
        if (!empty($this->inputGroups)) {

            //var_dump($this->inputGroups);
            ksort($this->inputGroups);
            foreach ($this->inputGroups as $group) {
                $formBody .= '<div class="row">';
                foreach ($group as $input) {
                    $formBody .= '<div class="col-md">';
                    $formBody .= $input;
                    $formBody .= '</div>';
                }

                $formBody .= '</div>';
            }
        }
        $this->formHTML .= $formBody;
        if (!$this->subForm) {
            $this->formHTML .= "</form>";
        }


        //var_dump($this->formHTML);
        //echo "<script> console.log('" . $this->formHTML . "');</script>";
        return $this->formHTML;
    }

    /**
     * addToRow
     *
     * Adds a form item to the specified row. If no column is supplied, the item is added to the end of the row.
     * If no form component is provided, the last form item that was created will be added.
     * @param mixed $rowKey The row index
     * @param mixed $colKey The column index
     * @return Form Description
     */
    public function addToRow($rowKey = null, $formComponent = null) {
        if (is_null($formComponent)) {
            $formComponent = $this->lastComponentBuilt;
        }

        if (is_null($rowKey)) {
            $rowKey = $this->currentRow;
        }
        //var_dump($formComponent);
        //var_dump($rowKey);

        $this->inputGroups[$rowKey][] = $formComponent;
        $this->currentRow = $rowKey;
        return $this;
    }

    /**
     *
     * @param type $colKey
     * @param type $rowKey
     * @return Form
     */
    public function addToCol($colKey, $rowKey = null) {
        if (isset($this->currentRow) and $rowKey == null) {
            $this->inputGroups[$this->currentRow][$colKey] = $this->lastComponentBuilt;
        } elseif ($rowKey != null) {
            $this->inputGroups[$rowKey][$colKey] = $this->lastComponentBuilt;
        }
        return $this;
    }

    public function addLastBuildToForm() {
        $this->formHTML .= $this->lastComponentBuilt;
    }

    /**
     *
     * @param type $formComponent
     * @param type $rowKey
     * @return Form
     */
    public function addToForm($formComponent, $rowKey = null) {
        $this->addToRow($rowKey, $formComponent);
        return $this;
    }

    /**
     *
     * @return Form
     */
    public function buildUpdateButton() {
        $this->lastComponentBuilt = '<div class="">
            <button class="btn btn-primary" type="submit">Update</button>
        </div>';
        return $this;
    }

    /**
     *
     * @return Form
     */
    public function small() {
        if (strpos($this->lastComponentBuilt, 'input-group')) {
            $this->lastComponentBuilt = str_replace('input-group', 'input-group col-md-3 mx-auto', $this->lastComponentBuilt);
            //var_dump("form-control found");
        }
        return $this;
    }

    public function medium() {
        if (strpos($this->lastComponentBuilt, 'input-group')) {
            $this->lastComponentBuilt = str_replace('input-group', 'input-group col-md-5 mx-auto', $this->lastComponentBuilt);
            //var_dump("form-control found");
        }
        return $this;
    }

    public function insertObjectIDInput($schema, $value) {
        //var_dump($schema);
        $this->lastComponentBuilt = '<input hidden type="hidden"
            name="' . $schema[Schema::NAME] . '"
            value="' . $value . '"/>';
        $this->addLastBuildToForm();
        return $this;
    }

    /**
     *
     * @return Form
     */
    public function buildCustomButton($text, $buttonClass, $target) {

        $this->lastComponentBuilt = '<ul class="nav justify-content-center nav-pills m-3">


    <li class="nav-item">
        <a class="nav-link btn btn-' . $buttonClass . '" href="' . $target . '">' . $text . '</a>
    </li>

</ul>';
        return $this;
    }

    public function buildSubmitButton($text, $buttonClass) {

        $this->lastComponentBuilt = '<div class="nav justify-content-center nav-pills m-3">



        <button type="submit" class="nav-link btn btn-' . $buttonClass . '">' . $text . '</button>


</div>';
        return $this;
    }

    private function preProcessName($name) {
        if (is_array($name)) {
            if (key_exists(Schema::NAME, $name)) {
                $name = $name[Schema::NAME];
            } else {
                $caller = backTrace();
                \system\app\AppLogger::get()->error("<br/>" . $caller["file"] . ":" . $caller["line"] . "<br/>" . 'Attempt to use an array as a form object name. ' . var_export($name, true));
                $name = 'App_Error_Occured_Check_Logs';
            }
        }
        return $name;
    }

    /**
     *
     * @param type $label
     * @param string $name
     * @param type $value
     * @param type $helpText
     * @param type $placeholder
     * @return Form
     */
    public function buildTextInput($label, $name, $value = null, $helpText = null, $placeholder = null) {
        //var_dump($name);
        $name = $this->preProcessName($name);
        $formComponent = $this->startInput($name, $label, $helpText);

        $formComponent .= '<input type="text" class="form-control text-center" name="' . $name . '" value="' . $value . '" placeholder="' . $placeholder . '"/>';
        $formComponent .= $this->formEnd;
        $this->lastComponentBuilt = $formComponent;
        return $this;
    }

    /**
     *
     * @param type $label
     * @param string $name
     * @param type $value
     * @param type $helpText
     * @param type $placeholder
     * @return Form
     */
    public function buildDropDownInput($label, $name, $array, $helpText = null) {
        //var_dump($name);
        $name = $this->preProcessName($name);
        $formComponent = $this->startInput($name, $label, $helpText);

        $formComponent .= '<select type="text" class="form-control text-center" name="' . $name . '">';
        foreach ($array as $option) {
            $formComponent .= '<option value="' . $option[1] . '">' . $option[0] . '</option>';
        }
        $formComponent .= '</select>';
        $formComponent .= $this->formEnd;
        $this->lastComponentBuilt = $formComponent;
        return $this;
    }

    public function center() {
        if (strpos($this->lastComponentBuilt, '<option')) {
            $this->lastComponentBuilt = str_replace('<option', '<option class="text-center"', $this->lastComponentBuilt);
            //var_dump("form-control found");
        }
        return $this;
    }

    /**
     *
     * @param type $label
     * @param string $name
     * @param type $value
     * @param type $helpText
     * @param type $placeholder
     * @return Form
     */
    public function buildPasswordInput($label, $name, $value = null, $helpText = null, $placeholder = null) {
        //var_dump($name);
        $name = $this->preProcessName($name);
        $formComponent = $this->startInput($name, $label, $helpText);

        $formComponent .= '<input type="password" class="form-control text-center" name="' . $name . '" value="' . $value . '" placeholder="' . $placeholder . '"/>';
        $formComponent .= $this->formEnd;
        $this->lastComponentBuilt = $formComponent;
        return $this;
    }

    public function appendInput($appendContent) {
        $append = '<div class="input-group-append">
    <span class="input-group-text">' . $appendContent . '</span>
  </div>';
        $this->lastComponentBuilt = substr($this->lastComponentBuilt, 0, strlen($this->lastComponentBuilt) - 6) . $append . $this->formEnd;
        return $this;
    }

    private function startInput($name, $label, $helpText = null) {
        $startInput = '<label class="font-weight-bold mb-0" for="' . $name . '">' . $label . '</label>';
        if (!empty($helpText)) {

            $startInput .= '<small id="' . $name . 'HelpBlock" class="form-text text-muted mt-0">' . $helpText . '</small>';
        }
        $startInput .= '<div class="input-group input-group-sm p-3 mb-2">';
        return $startInput;
    }

    public function buildBinaryInput($label, $name, $state, $helpText = null, $helpFunction = null) {
        //var_dump(boolval($state));

        $name = $this->preProcessName($name);
        $formComponent = $this->startInput($name, $label);

        if (!empty($helpText)) {
            $formComponent .= '<small id="' . $name . 'HelpBlock" class="form-text text-muted">';
            if (!is_null($helpText)) {
                $formComponent .= $helpText;
                if (!is_null($helpFunction)) {
                    ob_start();
                    $helpFunction();
                    $formComponent .= ob_get_clean();
                }
            }
            $formComponent .= '</small>';
        }
        $formComponent .= '<div class="row">';
        $formComponent .= ' <div class="col-md">';
        if (!$state) {
            $formComponent .= ' <input class="form-control-input" type="radio" name="' . $name . '" value="0" checked/>';
        } else {
            $formComponent .= ' <input class="form-control-input" type="radio" name="' . $name . '" value="0" />';
        }
        $formComponent .= '<label class="" for="' . $name . '">False</label>';
        $formComponent .= '</div>';
        $formComponent .= ' <div class="col-md">';
        if (!$state) {
            $formComponent .= ' <input class="form-control-input" type="radio" name="' . $name . '" value="1" checked/>';
        } else {
            $formComponent .= ' <input class="form-control-input" type="radio" name="' . $name . '" value="1" />';
        }
        $formComponent .= '<label class="" for="' . $name . '">True</label>';
        $formComponent .= '</div>';
        $formComponent .= '</div>';
        $formComponent .= $this->formEnd;
    }

}
