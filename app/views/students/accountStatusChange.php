


<h3>
    Student Account Lock/Unlock Manager
</h3>

<?php

use system\app\Form;

$form = new Form();

$form->buildTextInput('Username', 'username')
        ->medium()
        ->addToNewRow()
        ->buildDropDownInput('Action', 'action', $this->actionArray)
        ->small()
        ->addToNewRow()
        ->buildSubmitButton('Submit')
        ->addToNewRow();


echo $form->getFormHTML();
