<?php


use App\Api\Windows\WindowsRM;
use App\Forms\FormText;
use System\App\Forms\Form;
use System\App\Forms\FormButton;


$rebootPCForm = new Form('/tech/comp-mgmt', 'rebootPC');
$pcName = new FormText('Reboot a computer', 'Computer DNS Name or IP', 'destination');
$action = new FormText('', '', 'action', 'rebootPC');
$action->hidden();
$submit = new FormButton('Reboot');
$rebootPCForm->addElementToNewRow($pcName)
    ->addElementToNewRow($action)
    ->addElementToNewRow($submit);
echo $rebootPCForm->print();