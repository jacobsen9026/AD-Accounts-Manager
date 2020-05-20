


<h3>
    Student Search
</h3>

<?php

use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\App\Forms\FormText;

$form = new Form("/students/search", "StudentAccountStatus");
$button = new FormButton("Search");
$button->small();
$textBox = new FormText("Username", "Can also enter first or last name to search for username.", "usernameInput");
$textBox->autoCompleteStudentUsername()
        ->setId("usernameInput")
        ->appendIcon('<i class="fas fa-search"></i>')
        ->medium();
$form->addElementToNewRow($textBox)
        ->addElementToNewRow($button)
        ->setActionVariable($textBox);
echo $form->print();





