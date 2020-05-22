<?php

use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\App\Forms\FormText;
use App\Models\District\Group;
use System\App\Forms\FormHTML;

$form = new Form("/groups/delete", "GroupDelete");
$action = new FormText('', '', 'action', 'deleteGroup');
$action->hidden();
$button = new FormButton("Delete");
$button->small()
    ->setTheme('danger');
$text = new FormHTML();
$text->setHtml('<h5>Are you absolutely sure you want to delete this group? There is no undo to this action.<br><br> Be sure you are deleting the right group.</h5>');
$text->full();
$name = new FormText('', '', 'groupDN', $params["distinguishedName"]);
$name->medium()
    ->hidden();
$form->addElementToNewRow($name)
    ->addElementToNewRow($action)
    ->addElementToNewRow($text)
    ->addElementToNewRow($button);
echo $form->print();





