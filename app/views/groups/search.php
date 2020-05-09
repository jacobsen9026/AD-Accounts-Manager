


<h3>
    Group Search
</h3>

<?php

use system\app\forms\Form;
use system\app\forms\FormButton;
use system\app\forms\FormText;

$form = new Form("/groups/search", "GroupSearch");
$button = new FormButton("Search");
$button->small();
$textBox = new FormText("Group", "Can search by name, email, or description", "group");
$textBox->autoCompleteGroupName()
        ->setId("group")
        ->appendIcon('<i class="fas fa-search"></i>')
        ->medium();
$form->addElementToNewRow($textBox)
        ->addElementToNewRow($button)
        ->setActionVariable($textBox);
echo $form->print();





