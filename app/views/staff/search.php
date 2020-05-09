


<h3>
    Staff Search
</h3>

<?php

use system\app\forms\Form;
use system\app\forms\FormButton;
use system\app\forms\FormText;

$form = new Form("/staff/search", "StaffAccountStatus");
$button = new FormButton("Search");
$button->small();
$textBox = new FormText("Username", "Can also enter first or last name to search for username.", "username");
$textBox->autoCompleteStaffUsername()
        ->setId("username")
        ->appendIcon('<i class="fas fa-search"></i>')
        ->medium();
$form->addElementToNewRow($textBox)
        ->addElementToNewRow($button)
        ->setActionVariable($textBox);
echo $form->print();





