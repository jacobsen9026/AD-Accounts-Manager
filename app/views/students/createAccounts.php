


<h3>
    Student New User Creator
</h3>

<?php

use System\App\Forms\Form;

$grades = \App\Models\District\GradeDefinition::getDropdownArray();
$form = new Form();
$form->buildTextInput('First Name', 'firstName')
        ->addToNewRow()
        ->buildTextInput('Middle Name', 'middleName')
        ->medium()
        ->addToRow()
        ->buildTextInput('Last Name', 'lastName')
        ->addToRow()
        ->buildDropdownInput('Last Name', 'lastName')
        ->addToNewRow()
        ->buildPasswordInput('Password', 'password', null, "Leave blank for a new random password")
        ->medium()
        ->addToNewRow()
        ->buildSubmitButton('Submit')
        ->addToNewRow();


echo $form->getFormHTML();
