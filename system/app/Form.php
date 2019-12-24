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
use app\models\district\ActiveDirectory;
use app\models\district\GoogleApps;

class Form {

//put your code here
    private $formHTML;
    private $inputGroups;
    private $lastComponentBuilt;
    private $currentRow;
    private $formEnd = '</div>';
    private $subForm = false;
    private $logger;

    /**
     *
     * @param string $action The action attribute of the form tag
     * @param string $name The name attribute of the form tag
     * @param string $method
     */
    function __construct($action, $name = '', $method = 'post') {
        $this->formHTML = '<form name="' . $name . '" method="' . $method . '" action="' . $action . '">';
        $this->currentRow = 1;
        $this->logger = AppLogger::get();
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
                $formBody .= '<div class="row mb-2">';
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

    public function addToNewRow() {

        $rowKey = $this->currentRow + 1;

//var_dump($formComponent);
//var_dump($rowKey);

        $this->inputGroups[$rowKey][] = $this->lastComponentBuilt;
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
    public function buildUpdateButton($text = null) {
        if ($text == null) {
            $text = 'Update';
        }
        $this->lastComponentBuilt = '<div class="">
            <button class="btn btn-primary" type="submit">' . $text . '</button>
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
                $this->logger->error("<br/>" . $caller["file"] . ":" . $caller["line"] . "<br/>" . 'Attempt to use an array as a form object name. ' . var_export($name, true));
                $name = 'App_Error_Occured_Check_Logs';
            }
        }
        return $name;
    }

    /**
     *
     * @param string $label
     * @param const $name
     * @param string $value
     * @param string $helpText
     * @param string $placeholder
     * @return Form
     */
    public function buildTextInput($label, $name, $value = null, $helpText = null, $placeholder = null) {
//var_dump($name);
        $name = $this->preProcessName($name);
        $formComponent = $this->startInput($name, $label, $helpText);

        $formComponent .= '<input type="text" class="col form-control text-center" name="' . $name . '" value="' . $value . '" placeholder="' . $placeholder . '"/>';
        $formComponent .= $this->formEnd;
        $this->lastComponentBuilt = $formComponent;
        return $this;
    }

    /**
     *
     * @param string $label
     * @param const $name
     * @param string $value
     * @param string $helpText
     * @param string $placeholder
     * @return Form
     */
    public function buildTextAreaInput($label, $name, $value = null, $helpText = null, $placeholder = null) {
//var_dump($name);
        $name = $this->preProcessName($name);
        $formComponent = $this->startInput($name, $label, $helpText);

        $formComponent .= '<textarea type="text" class="h-100 form-control" name="' . $name . '" placeholder="' . $placeholder . '">' . $value . '</textarea>';
        $formComponent .= $this->formEnd;
        $this->lastComponentBuilt = $formComponent;
        $this->inline();
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

        $name = $this->preProcessName($name);
        $formComponent = $this->startInput($name, $label, $helpText);

        $formComponent .= '<select type="text" class="form-control text-center" name="' . $name . '">';
        foreach ($array as $option) {
            var_dump($option);
            if (is_array($option)) {
                $value = $option[1];
                $text = $option[0];
            } else {

                $value = $option;
                $text = $option;
            }
            $formComponent .= '<option value="' . $value . '">' . $text . '</option>';
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
        $append = '<div class="px-0 col-md-4 col-lg-3 col-xl-2">
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
        $startInput .= '<div class="row  p-3 h-100">';
        return $startInput;
    }

    /**
     *
     * @param string $label
     * @param string $name
     * @param bool $state
     * @param string $helpText
     * @param function $helpFunction
     * @return $this
     */
    public function buildBinaryInput($label, $name, $state, $helpText = null, $helpFunction = null) {
        //var_dump(boolval($state));
        $state = boolval($state);
        $name = $this->preProcessName($name);
        $formComponent = $this->startInput($name, $label);
        if (!empty($helpText)) {
            $formComponent .= '<div class="col "><div><small id="' . $name . 'HelpBlock" class="form-text text-muted">';
            if (!is_null($helpText)) {
                $formComponent .= $helpText;
                if (!is_null($helpFunction)) {
                    ob_start();
                    $helpFunction();
                    $formComponent .= ob_get_clean();
                }
            }
            $formComponent .= '</small></div>';
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
        if ($state) {
            $formComponent .= ' <input class="form-control-input" type="radio" name="' . $name . '" value="1" checked/>';
        } else {
            $formComponent .= ' <input class="form-control-input" type="radio" name="' . $name . '" value="1" />';
        }
        $formComponent .= '<label class="" for="' . $name . '">True</label>';
        $formComponent .= '</div>';
        $formComponent .= '</div>';
        $formComponent .= '</div>';


        $formComponent .= $this->formEnd;

        $this->lastComponentBuilt = $formComponent;
        //$this->inline();
        return $this;
    }

    public function generateADForm($objectID, $staffADSettings, $schema, $type = 'Staff') {
        $this->logger->info('Generating Staff AD Form for ' . $schema . ' ' . $objectID);
        $this->buildTextInput($type . ' Active Directory OU',
                        Schema::ACTIVEDIRECTORY_OU,
                        $staffADSettings[Schema::ACTIVEDIRECTORY_OU[Schema::COLUMN]],
                        'eg: /schools/school_name/',
                        ActiveDirectory::getField($schema, $objectID, Schema::ACTIVEDIRECTORY_OU, $type))
                ->addToRow(1)
                ->buildTextInput($type . ' Active Directory User Description',
                        Schema::ACTIVEDIRECTORY_DESCRIPTION,
                        $staffADSettings[Schema::ACTIVEDIRECTORY_DESCRIPTION[Schema::COLUMN]],
                        'eg: School_Name Staff',
                        ActiveDirectory::getField($schema, $objectID, Schema::ACTIVEDIRECTORY_DESCRIPTION, $type))
                ->addToRow(2)
                ->buildTextInput($type . ' Active Directory Group',
                        Schema::ACTIVEDIRECTORY_GROUP,
                        $staffADSettings[Schema::ACTIVEDIRECTORY_GROUP[Schema::COLUMN]],
                        'eg: School_NameStaff',
                        ActiveDirectory::getField($schema, $objectID, Schema::ACTIVEDIRECTORY_GROUP, $type))
                ->addToRow()
                ->buildTextInput($type . ' Active Directory Home Path',
                        Schema::ACTIVEDIRECTORY_HOME_PATH,
                        $staffADSettings[Schema::ACTIVEDIRECTORY_HOME_PATH[Schema::COLUMN]],
                        'eg: \\\\server\\share',
                        ActiveDirectory::getField($schema, $objectID, Schema::ACTIVEDIRECTORY_HOME_PATH, $type))
                ->appendInput(DIRECTORY_SEPARATOR . 'username')
                ->addToRow(3)
                ->buildTextInput($type . ' Active Directory Logon Script',
                        Schema::ACTIVEDIRECTORY_LOGON_SCRIPT,
                        $staffADSettings[Schema::ACTIVEDIRECTORY_LOGON_SCRIPT[Schema::COLUMN]],
                        'eg: logon.bat',
                        ActiveDirectory::getField($schema, $objectID, Schema::ACTIVEDIRECTORY_LOGON_SCRIPT, $type))
                ->addToRow(4);
        return $this;
    }

    public function inline() {

        $this->lastComponentBuilt = str_replace('<label', '<div class="row"><div class="col-md-4"><label"', $this->lastComponentBuilt);
//var_dump("form-control found");

        if (strpos($this->lastComponentBuilt, '</small>')) {
            $this->lastComponentBuilt = str_replace('</small>', '</small></div><div class="col-md-8">', $this->lastComponentBuilt);
//var_dump("form-control found");
        }
        if (strpos($this->lastComponentBuilt, '</div>')) {
            $this->lastComponentBuilt .= "</div></div>";
//var_dump("form-control found");
        }
        return $this;
    }

    public function generateGAForm($objectID, $staffGASettings, $schema, $type = 'Staff') {
        $this->logger->info('Generating Staff GA Form for ' . $schema . ' ' . $objectID);
        $this->buildTextInput($type . ' Google Apps OU',
                        Schema::GOOGLEAPPS_OU,
                        $staffGASettings[Schema::GOOGLEAPPS_OU[Schema::COLUMN]],
                        'eg: /schools/school_name/',
                        GoogleApps::getField($schema, $objectID, Schema::GOOGLEAPPS_OU, $type))
                ->addToRow(1)
                ->buildTextInput($type . ' Google Apps Group',
                        Schema::GOOGLEAPPS_GROUP,
                        $staffGASettings[Schema::GOOGLEAPPS_GROUP[Schema::COLUMN]],
                        'eg: School_NameStaff',
                        GoogleApps::getField($schema, $objectID, Schema::GOOGLEAPPS_GROUP, $type))
                ->addToRow(2)
                ->buildTextInput($type . ' Google Apps Other Groups',
                        Schema::GOOGLEAPPS_OTHER_GROUPS,
                        $staffGASettings[Schema::GOOGLEAPPS_OTHER_GROUPS[Schema::COLUMN]],
                        'eg: SpecialGroup',
                        GoogleApps::getField($schema, $objectID, Schema::GOOGLEAPPS_OTHER_GROUPS, $type))
                ->addToRow(2);

        return $this;
    }

}
