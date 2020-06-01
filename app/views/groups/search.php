<?php

use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\App\Forms\FormText;

$createButton = new FormButton("<i class=\"fas fa-plus\"></i>");
$createButton->tiny()
    ->setTheme("white")
    ->setId('create_new_group_button')
//    ->removeInputClasses(["w-100"])
    ->addInputClasses("text-success right")
    ->addElementClasses(" right pr-5 d-inline h-100 ");
$createModal = new \App\Models\View\Modal();
$createModal->setBody($this->view('/groups/create'))
    ->setId('createGroup')
    ->setTitle("Create New Group");
$createButton->addModal($createModal);
//echo $createButton->getElementHTML();
?>

    <div class="col">
        <h3 class="position-relative d-inline card-title text-center p-5- m-5">
            Group Search
        </h3>

        <?php
        echo $createButton->print();
        ?>
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





