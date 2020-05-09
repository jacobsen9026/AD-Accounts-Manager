


<h3>
    Student Search
</h3>

<?php

use system\app\forms\Form;
use system\app\forms\FormButton;
use system\app\forms\FormText;

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





