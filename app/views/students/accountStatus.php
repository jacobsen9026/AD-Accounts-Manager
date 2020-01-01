


<h3>
    Student Account Status
</h3>

<?php

use system\app\Form;

$form = new Form();
$form->buildTextInput('Username', 'username')
        ->medium()
        ->addToNewRow()
        ->buildSubmitButton('Submit')
        ->addToNewRow();


echo $form->getFormHTML();
