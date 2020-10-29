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
$userSearchBox = new FormText("Username", "Can also enter first or last name to search for username.", "usernameInput");
$userSearchBox->autoCompleteUsername()
    ->autofocus()
    ->setId("usernameInput")
    ->medium();

$appendImg = new \System\App\Forms\FormHTML();
$appendImg->setHtml('<i class="fas fa-search"></i>')
    ->addInputClasses('input-group-text text-center');

$inputGroup = new \System\App\Forms\FormElementGroup("Username", "Can also enter first or last name to search for username.");
$inputGroup->addElementToGroup($appendImg)
    ->addElementToGroup($userSearchBox)
    ->medium();

$form->addElementToNewRow($inputGroup)
    ->addElementToNewRow($button)
    ->setActionVariable($userSearchBox);
echo $form->print();





