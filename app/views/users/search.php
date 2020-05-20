


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
        ->appendIcon('<i class="fas fa-search"></i>')
        ->medium();
$form->addElementToNewRow($textBox)
        ->addElementToNewRow($button)
        ->setActionVariable($textBox);
echo $form->print();





