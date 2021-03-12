<?php

use App\Models\View\Javascript;
use App\Forms\FormText;
use System\App\Forms\FormButton;
use System\App\Forms\FormDropdown;
use System\App\Forms\FormDropdownOption;
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

$logonScript = new FormText(Lang::get('Logon Script'), '', 'logonscript');
$logonScript->medium();


$homeDrive = new FormDropdown(Lang::get('Home Drive'), '', 'homedrive');
$homeDrive->medium();
foreach (range('B', 'Z') as $letter) {
    $option = new FormDropdownOption($letter . ":", $letter);
    //$option->addInputClasses('text-center');
    $homeDrive->addOption($option);
}


$homePath = new FormText(Lang::get('Home Path'), '', 'homepath');
$homePath->medium();

$showPassword->setType('button')
    ->setInputClasses('h-100')
    ->setName('<i class="fas fa-eye-slash"></i>');
$showPasswordJS = Javascript::on($showPassword->getId(), '
console.log("clicked");
var input = $("#' . $password->getId() . '");
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
$passwordInput = new FormElementGroup('Password', '', 'passwordInput');
$passwordInput->addElementToGroup($password)
    ->addElementToGroup($showPassword);

$email = new FormText(Lang::get('Email Address'), '', 'email');
$email->medium();
$ouInput = new FormText(Lang::get('OU'), "", "ou");
$ouInput->autoCompleteOU()
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
//echo $form->print();
?>
<form action="/users/create" method="post" name="UserCreate" id="UserCreateForm">

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#general" role="tab" aria-controls="home"
               aria-selected="true">General</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="address-tab" data-toggle="tab" href="#address" role="tab" aria-controls="Address"
               aria-selected="false">Address</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="account-tab" data-toggle="tab" href="#account" role="tab" aria-controls="account"
               aria-selected="false">Account</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
            <?php
            echo $fname->print();
            echo $mname->print();
            echo $lname->print();
            echo $fullname->print();
            echo $description->print();
            ?>
        </div>
        <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">......</div>
        <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="account-tab">
            <?php
            echo $logon->print();
            echo $passwordInput->print();
            echo $email->print();
            echo $ouInput->print();
            echo $logonScript->print();
            echo $homeDrive->print();
            echo $homePath->print();
            ?>
        </div>
    </div>
    <?= $button->print(); ?>
</form>


