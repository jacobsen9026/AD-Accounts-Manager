


<h3>
    Student Password Reset
</h3>

<?php

use system\app\forms\Form;

$form = new Form();
$form->buildUserSearchInput()
        ->medium()
        ->addToNewRow()
        ->buildPasswordInput('Password', 'password', null, "Leave blank for a new random password")
        ->medium()
        ->addToNewRow()
        ->buildSubmitButton('Submit')
        ->addToNewRow();


echo $form->getFormHTML();
