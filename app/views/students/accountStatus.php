


<h3>
    Student Account Status
</h3>

<?php

use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\App\Forms\FormText;

$form = new Form("/students/account-status", "StudentAccountStatus");
$button = new FormButton("Submit");
$button->small();
$textBox = new FormText("Username", "Can also enter first or last name to search for username.", "username");
$textBox->autoCompleteStudentUsername()
        ->appendIcon('<i class="fas fa-search"></i>');
$form->addElementToNewRow($textBox)
        ->addElementToNewRow($button);
echo $form->print();





