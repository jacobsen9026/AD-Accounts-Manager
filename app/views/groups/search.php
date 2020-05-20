
<?php

use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\App\Forms\FormText;

$createButton = new FormButton("+");
$createButton->tiny()
        ->setTheme("success")
        ->removeInputClasses("w-100")
        ->addInputClasses("position-absolute right-10")
        ->addElementClass("top right pr-5");
$createModal = new \App\Models\View\Modal();
$createModal->setBody($this->view('/groups/create'))
        ->setId('createGroup')
        ->setTitle("Create New Group");
$createButton->setModal($createModal);
echo $createButton->print();
?>

<div class="ml-5">
    <h3 >
        Group Search
    </h3>
</div>
<?php
$form = new Form("/groups/search", "GroupSearch");
$button = new FormButton("Search");
$button->small();
$textBox = new FormText("Group", "Can search by name, email, or description", "group");
$textBox->autoCompleteGroupName()
        ->setId("group")
        ->appendIcon('<i class="fas fa-search"></i>')
        ->medium();


$form//->addElementToNewRow($createButton)
        ->addElementToNewRow($textBox)
        ->addElementToNewRow($button)
        ->setActionVariable($textBox);
echo $form->print();





