
<?php

use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\App\Forms\FormText;

$form = new Form("/groups/create", "GroupCreate");
$button = new FormButton("Create");
$button->small();
$name = new FormText('Group Name', '', 'name');
$name->medium();
$description = new FormText('Description', '', 'description');
$description->medium();
$email = new FormText('Email Address', '', 'email');
$email->medium();
$ouInput = new FormText("OU", "Can search by name, email, or description", "ou");
$ouInput->autoCompleteOU()
        ->setId("ou")
        // ->appendIcon('<i class="fas fa-search"></i>')
        ->large();
$form->addElementToNewRow($name)
        ->addElementToCurrentRow($description)
        ->addElementToNewRow($email)
        ->addElementToNewRow($ouInput)
        ->addElementToNewRow($button);
echo $form->print();





