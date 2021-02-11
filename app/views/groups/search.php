<?php

use App\Models\User\PermissionHandler;
use App\Models\User\PermissionLevel;
use App\Models\View\Modal;
use System\App\Forms\Form;
use System\App\Forms\FormButton;
use App\Forms\FormText;
use System\App\Forms\FormElementGroup;
use System\App\Forms\FormHTML;
use System\Lang;

$createButton = new FormButton("<i class=\"fas fa-plus\"></i>");
$createButton->tiny()
    ->setTheme("white")
    ->setTooltip('Create a new group')
    ->setId('create_new_group_button')
//    ->removeInputClasses(["w-100"])
    ->addInputClasses("text-success right")
    ->addElementClasses(" right pr-5 d-inline h-100 ");
$createModal = new Modal();
$createModal->setBody($this->modal('createGroup'))
    ->setId('createGroup')
    ->setTitle("Create New Group");
$createButton->addModal($createModal);
?>

    <div class="col mb-2">
        <h3 class=" d-inline card-title text-center">
            <?= Lang::get("Group Search"); ?>
        </h3>

        <?php
        if (PermissionHandler::hasGroupPermissions(PermissionLevel::GROUP_ADD)) {
            echo $createButton->print();
        }
        ?>
    </div>
<?php
$form = new Form("/groups/search", "GroupSearch");
$button = new FormButton(Lang::get("Search"));
$button->small();
$groupSearchBox = new FormText(Lang::get("Group"), Lang::getHelp("Group_Search"), "group");
$groupSearchBox->autoCompleteGroupName()
    ->autofocus()
// ->appendIcon('<i class="fas fa-search"></i>')
    ->medium();
$appendImg = new FormHTML();
$appendImg->setHtml('<i class="fas fa-search"></i>')
    ->addInputClasses('input-group-text text-center');

$inputGroup = new FormElementGroup(Lang::get("Group"), Lang::getHelp("Group_Search"));
$inputGroup->addElementToGroup($appendImg)
    ->addElementToGroup($groupSearchBox)
    ->medium();
$form
    ->addElementToNewRow($inputGroup)
    ->addElementToNewRow($button)
    ->setActionVariable($groupSearchBox);
echo $form->print();





