


<h3>
    Staff Account Status
</h3>

<?php

use system\app\Form;

$form = new Form();
$form->buildUserSearchInput(false)
        ->addToNewRow()
        ->buildSubmitButton('Submit')
        ->addToRow(100);


echo $form->getFormHTML();




