<?php

use App\Models\User\PermissionHandler;
use App\Models\User\PermissionLevel;
use App\Models\View\Modal;
use System\App\Forms\FormButton;
use System\App\Forms\FormElementGroup;
use System\App\Forms\FormHTML;
use App\Forms\FormText;
use System\App\Forms\Form;
use System\Lang;


$createButton = new FormButton("<i class=\"fas fa-plus\"></i>");
$createButton->tiny()
    ->setTheme("white")
    ->setTooltip('Create a new user')
    ->setId('create_new_user_button')
//    ->removeInputClasses(["w-100"])
    ->addInputClasses("text-success right")
    ->addElementClasses(" right pr-5 d-inline h-100 ");
$createModal = new Modal();
$createModal->setBody($this->modal('createUser'))
    ->setId('createUser')
    ->setTitle("Create New User");
$createButton->addModal($createModal);

?>
<div class="col mb-2">
    <h3 class="d-inline card-title text-center">
        <?= Lang::get("User Search"); ?>
    </h3>
    <?php
    if (PermissionHandler::hasGroupPermissions(PermissionLevel::GROUP_ADD)) {
        echo $createButton->print();
    }
    ?>
</div>
<?php


$form = new Form("/users/search", "UserSearch");
$button = new FormButton(Lang::get("Search"));
$button->small();
$userSearchBox = new FormText(Lang::get("Username"), Lang::getHelp("User_Search"), "usernameInput");
$userSearchBox->autoCompleteUsername()
    ->autofocus()
    ->setId("usernameInput")
    ->medium();

$appendImg = new FormHTML();
$appendImg->setHtml('<i class="fas fa-search"></i>')
    ->addInputClasses('input-group-text text-center');

$inputGroup = new FormElementGroup(Lang::get("Username"), Lang::getHelp("User_Search"));
$inputGroup->addElementToGroup($appendImg)
    ->addElementToGroup($userSearchBox)
    ->medium();

$form->addElementToNewRow($inputGroup)
    ->addElementToNewRow($button)
    ->setActionVariable($userSearchBox);


echo $form->print();





