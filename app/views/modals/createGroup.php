<?php

use App\Forms\FormText;
use System\App\Forms\Form;
use System\App\Forms\FormButton;

use System\Lang;

$form = new Form("/groups/create", "GroupCreate");
$button = new FormButton(Lang::get("Create"));
$button->small();
$name = new FormText(Lang::get('Group Name'), '', 'name');
$name->medium();
$description = new FormText(Lang::get('Description'), '', 'description');
$description->medium();
$email = new FormText(Lang::get('Email Address'), '', 'email');
$email->medium();
$ouInput = new FormText(Lang::get('OU'), "", "ou");
$ouInput->autoCompleteOU()
    // ->appendIcon('<i class="fas fa-search"></i>')
    ->large();
$form->addElementToNewRow($name)
    ->addElementToCurrentRow($description)
    ->addElementToNewRow($email)
    ->addElementToNewRow($ouInput)
    ->addElementToNewRow($button);
echo $form->print();





