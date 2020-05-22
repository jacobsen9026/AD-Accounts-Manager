<h3>
    Student Password Reset
</h3>

<?php

use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\App\Forms\FormText;


$form = new Form("/students/reset-password", "StudentPasswordChange");
$button = new FormButton("Submit");
$button->small();
$textBox = new FormText("Username", "Can also enter first or last name to search for username.", "username");
$textBox->autoCompleteStudentUsername()
    ->appendIcon('<i class="fas fa-search"></i>');
$passwordBox = new FormText("New  Password", "Leave blank for a new random password", "password");
$passwordBox->isPassword();
$form->addElementToNewRow($textBox)
    ->addElementToNewRow($passwordBox)
    ->addElementToNewRow($button);
echo $form->print();
