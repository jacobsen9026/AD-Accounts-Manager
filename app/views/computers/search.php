<?php


use App\Api\Windows\WindowsRM;
use App\Forms\FormText;
use App\Models\View\Javascript;
use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\App\Forms\FormElementGroup;


$selectPCForm = new Form('/computers/search', 'findPC');
$pcName = new FormText('Find a computer', 'Computer DNS Name or IP', 'destination');
$pcName->autoCompleteComputerName()
    ->autofocus();
$submit = new FormButton('Search');
$submit->tiny();
$searchGroup = new FormElementGroup('Find a computer', 'Computer DNS Name or IP');
$searchGroup->addElementToGroup($pcName)
    ->addElementToGroup($submit);
$selectPCForm->addElementToNewRow($pcName)
    ->addElementToNewRow($submit);
$selectPCForm->setActionVariable($pcName);
//$selectPCForm->addElementToNewRow($searchGroup);
echo $selectPCForm->print();


?>
