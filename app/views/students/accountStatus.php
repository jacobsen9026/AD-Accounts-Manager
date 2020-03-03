


<h3>
    Student Account Status
</h3>

<?php

use system\app\forms\Form;
use system\app\forms\FormButton;
use system\app\forms\FormText;

$form = new Form("/students/account-status", "StudentAccountStatus");
$button = new FormButton("Submit");
//var_dump($button->getHTML());
//echo $button->getHTML();
$button = new FormButton("Submit");
$button->small();
$textBox = new FormText("Username", "Can also enter first or last name to search for username.", "username");
$textBox->autoCompleteUsername()
        ->appendIcon('<i class="fas fa-search"></i>');
$form->addElementToNewRow($textBox)
        ->addElementToNewRow($button);
var_dump($form->print());
echo $form->print();





