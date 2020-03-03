
<h3 class="pt-3">Create New District</h3>
<?php

use system\app\forms\Form;

$form = new Form('/settings/districts/create');
$form->buildTextInput('District Name', 'name')
        ->small()
        ->addToNewRow()
        ->buildSubmitButton('Submit')
        ->addToNewRow();
echo $form->getFormHTML();


