<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app\forms;

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
use app\models\district\ActiveDirectory;
use app\models\district\GoogleApps;
use system\app\AppLogger;

class Form {

    private $method = "post";
    private $action;
    private $name;
    private $style;




    /* A 2D array that represents the layout of the form */
    private $elements;
//put your code here
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
     * This essentially builds the form tag
     *
     * @param string $action The action attribute of the form tag
     * @param string $name The name attribute of the form tag
     * @param string $method
     */
    function __construct($action = null, $name = '', $method = 'post') {
// Check if action is null and if so set to current URL
        if (is_null($action)) {
            $action = $_SERVER["REQUEST_URI"];
        }
        $this->action = $action;
        $this->method = $method;
        $this->name = $name;
// Build form opening tag from construct parameters
        $this->formHTML = '<form class="pb-3" name="' . $this->name . '" method="' . $this->method . '" action="' . $this->action . '" enctype="multipart/form-data">';
// Initialize form row to 1
        $this->currentRow = 0;
// Initialize form logger
        $this->logger = AppLogger::get();
    }

    public function setStyle($style) {
        $this->style = $style;
        return $this;
    }

    public function addElementToCurrentRow($element) {

        $this->elements[$this->currentRow][] = $element;

        return $this;
//var_dump($this->elements);
    }

    public function addElementToNewRow($element) {

        $this->currentRow++;
        $this->addElementToCurrentRow($element);

        return $this;
    }

    public function print() {
        $html = "<form action='$this->action' method='$this->method' name='$this->name' style='$this->style'>";
        foreach ($this->elements as $row) {
            foreach ($row as $element) {
                $html .= $element->getHTML();
            }
        }
        return $html;
    }

    /**
     * subForm
     * Designates the form to be drawn with no form tags surrounding the components
     *
     * @return Form
     */
    public function subForm() {
// Overwrite formHTML with nothing to erase default form opening tag
        $this->formHTML = '';
// Set flag variable to tell getFormHTML that this is a subForm
        $this->subForm = true;
        return $this;
    }

    /**
     * getFormHTML
     * This is the final step in building a form.
     * Accounting for all previously set options the form HTML is generated and returned as a string
     *
     * @return string
     */
    public function getFormHTML() {
        $formBody = '';
// Create display tables for input groups
        if (!empty($this->inputGroups)) {
// Sort components by row
            ksort($this->inputGroups);
            foreach ($this->inputGroups as $group) {
                $formBody .= '<div class="row mx-0 mb-2">';
                foreach ($group as $input) {
                    $formBody .= '<div class="col-md">';
                    $formBody .= $input;
                    $formBody .= '</div>';
                }

                $formBody .= '</div>';
            }
        }
        $this->formHTML .= $formBody;
// If not a subForm close the form tag
        if (!$this->subForm) {
            $this->formHTML .= "</form>";
        }
        return $this->formHTML;
    }

    /**
     *
     * @return Form
     */
    public function addSeperator() {


        $name = $this->preProcessName(null);
        $formComponent = $this->startInput(null, null, null);

        $formComponent .= '<input style="visibility:hidden;" type="text" class="col form-control text-center" hidden /><br/><br/>';
        $formComponent .= $this->componentEnd;
        $this->lastComponentBuilt = $formComponent;
        $this->addToNewRow();
        return $this;
    }

    /**
     * addToRow
     *
     * Adds a form item to the specified row. If no column is supplied, the item is added to the end of the row.
     * If no form component is provided, the last form item that was created will be added.
     *
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

        $this->inputGroups[$rowKey][] = $formComponent;
        $this->currentRow = $rowKey;
        return $this;
    }

    /**
     * addToNewRow
     * Adds a form component built to a new row at the end of the list
     * If no form component is provided, the last form item that was created will be added.
     *
     *
     * @param type $formComponent
     * @return $this
     */
    public function addToNewRow($formComponent = null) {
//var_dump($formComponent);
//$this->logger->debug($formComponent);
        if (is_null($formComponent)) {

            $formComponent = $this->lastComponentBuilt;
        }
        $rowKey = $this->currentRow + 1;
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

    /**
     *
     */
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
        if (is_null($rowKey)) {
            $this->currentRow++;
            $this->addToRow($this->currentRow, $formComponent);
            return $this;
        }
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
        if (strpos($this->lastComponentBuilt, 'col form-control')) {
            $this->lastComponentBuilt = str_replace('col form-control', 'col-md-6 col-lg-4 col-xl-2 form-control mx-auto', $this->lastComponentBuilt);
//var_dump("form-control found");
        }
        return $this;
    }

    public function medium() {
        if (strpos($this->lastComponentBuilt, 'col form-control')) {
            $this->lastComponentBuilt = str_replace('col form-control', 'col-md col-lg-8 col-xl-6 form-control mx-auto', $this->lastComponentBuilt);
//var_dump("form-control found");
        }
        return $this;
    }

    public function addID($id) {
        $this->lastID = $id;
        if (strpos($this->lastComponentBuilt, '/>')) {
            $this->lastComponentBuilt = str_replace('/>', ' id="' . $id . '" />', $this->lastComponentBuilt);
//var_dump("form-control found");
        }
        return $this;
    }

    public function addAutoComplete($sourceURL, $minLength = 1) {
        $jsScript = '<script>

    $(function () {


    var getData = function (request, response) {
        $.getJSON(
            "' . $sourceURL . '" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
    console.log("select");
    console.log(ui);
    console.log(event);

        $("#' . $this->lastID . '").val(ui.item.value);
        return false;
    }

    $("#' . $this->lastID . '").autocomplete({
        source: getData,
        select: selectItem,
        minLength: ' . $minLength . ',
        change: function() {
            $("#' . $this->lastID . '").css("display", 2);
        }
    });
});

</script>';
        $this->logger->debug(htmlspecialchars($this->lastComponentBuilt));

        if (strpos($this->lastComponentBuilt, '</div>')) {
            $this->lastComponentBuilt = str_replace('</div>', '</div>' . $jsScript, $this->lastComponentBuilt);
//var_dump("form-control found");
        }

        return $this;
    }

    public function onType($jsFunction) {
        if (strpos($this->lastComponentBuilt, '>')) {
            $this->lastComponentBuilt = str_replace('>', ' onkeypress="' . $jsFunction . '">', $this->lastComponentBuilt);
//var_dump("form-control found");
        }
        return $this;
    }

    public function insertObjectIDInput($schema, $value) {
//var_dump($schema);
        $hiddenInput = '<input hidden type="hidden"
            name="' . $schema[Schema::NAME] . '"
            value="' . $value . '"/>';
        $this->formHTML .= $hiddenInput;
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

    public function buildSubmitButton($text, $buttonClass = 'primary') {

        $this->lastComponentBuilt = '<div class="nav justify-content-center nav-pills m-3">



        <button type="submit" class="nav-link btn btn-' . $buttonClass . '">' . $text . '</button>


</div>';
        return $this;
    }

    /**
     * Providing true or no parameter will result in a student search
     * Passing false as a parameter will result in a staff search
     *
     * @param type $student
     * @return $this
     */
    public function buildUserSearchInput($searchStudents = true) {
        $this->buildTextInput('Username', 'username', null, \system\Lang::getHelp('User Search'))
                ->addID("username");
        if ($searchStudents) {
            $this->addAutoComplete("/api/ldap/autocompleteStudent/", 2);
        } else {
            $this->addAutoComplete("/api/ldap/autocompleteStaff/", 2);
        }
        $this->medium()
                ->prependInput('<i class="fas fa-search"></i>');

        $this->logger->debug(htmlspecialchars($this->lastComponentBuilt));
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

        $formComponent .= '<input type="text" class="col form-control text-center  ml-0 mr-auto" name="' . $name . '"';
        if (isset($value)) {
            $formComponent .= ' value="' . $value . '"';
        }
        if (isset($placeholder)) {
            $formComponent .= ' placeholder="' . $placeholder . '"';
        }
        $formComponent .= '/>';
        $formComponent .= $this->componentEnd;
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
    public function buildErrorOutput($output) {
//var_dump($name);
//$name = $this->preProcessName($name);
        $formComponent = $this->startInput('', '', '');

        $formComponent .= $output;
        $formComponent .= $this->componentEnd;
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
    public function buildStatusCheck($label, $value, $helpText = null, $tooltip = null) {
//var_dump($value);
        $formComponent = $this->startInput(null, $label, $helpText);

        $formComponent .= '<div class="col-md text-center ">';
        $valueDisplay = '';
        if ($value == '1') {

            $classes = 'fas fa-check-circle text-success';
        } else {
            $valueDisplay = '<p class="mb-0">' . $value . '</p>';
            $classes = 'fas fa-times-circle text-danger';
        }
        $formComponent .= '<h1><i class="' . $classes . '"  data-toggle="tooltip" data-placement="top" title="' . $tooltip . '"></i></h1>' . $valueDisplay;

        $formComponent .= '</div>';
        $formComponent .= $this->componentEnd;
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
    public function buildAJAXStatusCheck($label, $target, $data = null, $helpText = null) {
//var_dump($value);
        $formComponent = $this->startInput(null, $label, $helpText);
        $label = strtolower(str_replace(" ", "", $label));

        $formComponent .= '<div class="col-md text-center" id="' . $label . 'ajaxOutput">';
        $formComponent .= '<button type="button" class="btn btn-primary" id="' . $label . 'button">Perorm Check</button>';
        $formComponent .= '</div>';


        $formComponent .= $this->componentEnd;
        $postData = '';
        if (!is_null($data)) {
            foreach ($data as $entry) {
                $postData .= $entry[0] . ': ' . $entry[1];
            }
        }
        $formComponent .= "<script>
                    $('#" . $label . "button').on('click', function () {
                        console.log('works');
                        $('#" . $label . "ajaxOutput').html(" . '"' . "<span class='spinner-border text-primary'>" . '"' . ");
                        $.post('$target', {" . $postData . "},
                                function (data) {
                                    //request completed
                                    //now update the div with the new data
                                    $('#" . $label . "ajaxOutput').html(data);
                }
                );
                //$('#" . $label . "ajaxOutput').slideDown('slow');
                });

        </script>";
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
    public function buildFileInput($name, $label, $helpText = null, $acceptedFileTypes = null) {

        $formComponent = '<div class="row p-3 h-100">';
        $formComponent .= '<label class="d-block w-100 font-weight-bold" for="' . $name . '">' . $label . '</label><br/>'
                . '<div class="d-block w-100">';
        $formComponent .= '<input type="file" ';
        if (!is_null($acceptedFileTypes)) {
            $formComponent .= ' accept = "' . $acceptedFileTypes . '" ';
        }
        $formComponent .= 'name="' . $name . '" id="' . $name . '">';
        $formComponent .= '<input type="text" name="somethingelse" hidden />';
        $formComponent .= '</div>';
//      $formComponent = '<div class="row p-3 h-100 custom-file">';
//$formComponent .= '<input type="file" class="custom-file-input" id="' . $name . '" name="' . $name . '"/>';
//$formComponent .= ' <label class=" custom-file-label" for="' . $name . '">Choose file</label>';

        $formComponent .= $this->componentEnd;
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
        $formComponent .= $this->componentEnd;
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

        $formComponent .= '<select type="text" class="col form-control custom-select text-center" name="' . $name . '">';
        foreach ($array as $option) {
//var_dump( $option);
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
        $formComponent .= $this->componentEnd;
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

        $formComponent .= '<input type="password" class="col form-control text-center" name="' . $name . '" value="' . $value . '" placeholder="' . $placeholder . '"/>';
        $formComponent .= $this->componentEnd;
        $this->lastComponentBuilt = $formComponent;
        return $this;
    }

    public function disable() {
        if (strpos($this->lastComponentBuilt, '<input ')) {
            $this->lastComponentBuilt = str_replace('<input ', '<input disabled ', $this->lastComponentBuilt);
//var_dump("form-control found");
        }
        return $this;
    }

    public function appendInput($appendContent = null) {
        if (is_null($appendContent)) {
            $appendContent = $this->lastComponentBuilt;
        }
        $append = '<div class="px-0 col-sm-auto">
            <span class="input-group-text">' . $appendContent . '</span>
        </div>';
        $this->lastComponentBuilt = substr($this->lastComponentBuilt, 0, strlen($this->lastComponentBuilt) - 6) . $append . $this->componentEnd;
        return $this;
    }

    public function prependInput($prependContent = null) {
        if (is_null($prependContent)) {
            $prependContent = $this->lastComponentBuilt;
        }
        $prepend = '<div class="px-0 col-auto ml-auto mr-0">
            <span class="input-group-text h-100 text-center">' . $prependContent . '</span>
        </div><div>';
//var_dump($this->lastComponentBuilt);
        $exploded = explode('<div class="row p-3 h-100 ui-widget">', $this->lastComponentBuilt);
        $this->lastComponentBuilt = $exploded[0] . '<div class="row p-3 h-100 ui-widget">' . $prepend . $exploded[1] . $this->componentEnd;
        if (strpos($this->lastComponentBuilt, 'mx-auto')) {
            $this->lastComponentBuilt = str_replace('mx-auto', '', $this->lastComponentBuilt);
//var_dump("form-control found");
        }
        if (strpos($this->lastComponentBuilt, 'col-md col-lg-8 col-xl-6')) {
            $this->lastComponentBuilt = str_replace('col-md col-lg-8 col-xl-6', 'col', $this->lastComponentBuilt);
//var_dump("form-control found");
        }
        return $this;
    }

    private function startInput($name, $label, $helpText = null) {
        $labelClass = 'font-weight-bold mb-0';
        $startInput = '<label class="' . $labelClass . '" for="' . $name . '">' . $label . '</label>';
        if (!empty($helpText)) {

            $startInput .= '<small id="' . $name . 'HelpBlock" class="form-text text-muted mt-0">' . $helpText . '</small>';
        }
        $startInput .= '<div class="row p-3 h-100 ui-widget">';
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

//var_dump($label);
//var_dump(boolval($state));

        $state = boolval($state);
        $name = $this->preProcessName($name);
        $formComponent = $this->startInput($name, $label, $helpText);
        $formComponent .= '<div class="col">';


        if (!is_null($helpFunction)) {
            ob_start();
            $helpFunction();
            $formComponent .= ob_get_clean();
        }

// $formComponent .= '</small></div></div>';



        $formComponent .= '<div class="row">';
        $formComponent .= ' <div class="col-md custom-radio custom-control">';
        $inputClass = 'custom-control-input';
        $common = '<input class="' . $inputClass . '" type="radio" name="' . $name . '"';


        if (!$state) {
            $formComponent .= $common . 'id="' . $name . '_FALSE" value="0" checked/>';
        } else {
            $formComponent .= $common . 'id="' . $name . '_FALSE" value="0" />';
        }


        $formComponent .= '<label class="custom-control-label" for="' . $name . '_FALSE">False</label>';
        $formComponent .= '</div>';
        $formComponent .= ' <div class="col-md custom-radio custom-control">';


        if ($state) {
            $formComponent .= $common . 'id="' . $name . '_TRUE"" value="1" checked/>';
        } else {
            $formComponent .= $common . 'id="' . $name . '_TRUE"" value="1" />';
        }
        $formComponent .= '<label class="custom-control-label" for="' . $name . '_TRUE">True</label>';
        $formComponent .= '</div>';
        $formComponent .= '</div>';
        $formComponent .= '</div>';


        $formComponent .= $this->componentEnd;

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
            $this->lastComponentBuilt = str_replace('</small>', '</small></div><div class = "col-md-8">', $this->lastComponentBuilt);
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
