<h3>
    User Search
</h3>

<?php

use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\App\Forms\FormText;

$form = new Form("/users/search", "UserSearch");
$button = new FormButton("Search");
$button->small();
$textBox = new FormText("Username", "Can also enter first or last name to search for username.", "usernameInput");
$textBox->autoCompleteUsername()
    ->setId("usernameInput")
    ->medium();

$appendImg = new \System\App\Forms\FormHTML();
$appendImg->setHtml('<i class="fas fa-search"></i>')
    ->addInputClasses('input-group-text text-center');

$inputGroup = new \System\App\Forms\FormElementGroup("Username", "Can also enter first or last name to search for username.");
$inputGroup->addElementToGroup($appendImg)
    ->addElementToGroup($textBox)
    ->medium();

$form->addElementToNewRow($inputGroup)
    ->addElementToNewRow($button)
    ->setActionVariable($textBox);
echo $form->print();





