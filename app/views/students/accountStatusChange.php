


<h3>
    Student Account Lock/Unlock Manager
</h3>

<?php

use system\app\forms\Form;

$actionArray = [["Unlock", "unlock"]];
if ($this->user->privilege >= \app\models\user\Privilege::TECH) {
    $actionArray[] = ["Lock", "lock"];
}
$form = new Form();

$form->buildUserSearchInput()
        ->addToNewRow()
        ->buildDropDownInput('Action', 'action', $actionArray)
        ->small()
        ->addToNewRow()
        ->buildSubmitButton('Submit')
        ->addToNewRow();


echo $form->getFormHTML();
