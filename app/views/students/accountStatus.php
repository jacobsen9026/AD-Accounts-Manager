


<h3>
    Student Account Status
</h3>

<?php

use system\app\Form;

$form = new Form();
$form->buildUserSearchInput()
        ->addToNewRow()
        ->buildSubmitButton('Submit')
        ->addToRow(100);


echo $form->getFormHTML();




