<?php

use App\Models\View\Javascript;
use App\Forms\FormText;
use System\App\Forms\FormButton;
use System\App\Forms\FormElementGroup;
use System\App\Forms\Form;
use System\Lang;

$form = new Form("/users/create", "UserCreate");
$button = new FormButton(Lang::get("Create"));
$button->medium();
$fname = new FormText(Lang::get('First Name'), '', 'fname');
$fname->medium();
$mname = new FormText(Lang::get('Initials'), '', 'mname');
$mname->small();
$lname = new FormText(Lang::get('Last Name'), '', 'lname');
$lname->medium();
$fullname = new FormText(Lang::get('Full Name'), '', 'fullname');
$fullname->medium();

$description = new FormText(Lang::get('Description'), '', 'description');
$description->medium();
$logon = new FormText(Lang::get('Logon Name'), '', 'logonname');
$logon->medium();
$password = new FormText(Lang::get('Password'), '', 'password');
$password->medium()
->isPassword();

$showPassword = new FormButton('showPass');
$showPassword->setType('button')
    ->setInputClasses('h-100')
    ->setName('<i class="fas fa-eye-slash"></i>');
$showPasswordJS = Javascript::on($showPassword->getId(),'
console.log("clicked");
var input = $("#'.$password->getId().'");
console.log(this.innerHTML);
if(input.attr("type") === "password"){
    input.attr("type","text");
    this.innerHTML = "<i class=\"fas fa-eye\"></i>"
}else{
    input.attr("type","password");
        this.innerHTML = "<i class=\"fas fa-eye-slash\"></i>"
}
');
$showPassword->setScript($showPasswordJS);
$passwordInput = new FormElementGroup('Password','','passwordInput');
$passwordInput->addElementToGroup($password)
    ->addElementToGroup($showPassword);

$email = new FormText(Lang::get('Email Address'), '', 'email');
$email->medium();
$ouInput = new FormText(Lang::get('OU'), "", "ou");
$ouInput->autoCompleteOU()
    ->setId("ou")
    // ->appendIcon('<i class="fas fa-search"></i>')
    ->large();
$form->addElementToNewRow($fname)
    ->addElementToCurrentRow($mname)
    ->addElementToNewRow($lname)
    ->addElementToNewRow($fullname)
    ->addElementToNewRow($logon)
    ->addElementToNewRow($passwordInput)
    ->addElementToNewRow($description)
    ->addElementToNewRow($email)
    ->addElementToNewRow($ouInput)
    ->addElementToNewRow($button);
echo $form->print();





