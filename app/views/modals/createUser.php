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
$button->medium()
    ->attachToForm($form);
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
$groups = new FormText('Groups', 'Member Of', 'groups');
$copyFrom = new FormText('Copy group memberships from', 'Enter a user to duplicate group membership from', 'copyFrom');
$copyFrom->autoCompleteUsername();
$form->addElementToNewRow($fname)
    ->addElementToCurrentRow($mname)
    ->addElementToNewRow($lname)
    ->addElementToNewRow($fullname)
    ->addElementToNewRow($logon)
    ->addElementToNewRow($passwordInput)
    ->addElementToNewRow($description)
    ->addElementToNewRow($email)
    ->addElementToNewRow($ouInput);
$form2 = new Form();
$form2->addElementToNewRow($logon)
    ->addElementToCurrentRow($passwordInput)
    ->addElementToNewRow($email)
    ->addElementToNewRow($ouInput)
    ->addElementToNewRow($logonScript)
    ->addElementToNewRow($homeDrive)
    ->addElementToCurrentRow($homePath);
$form3 = new Form();
$form3->addElementToNewRow($logon)
    ->addElementToCurrentRow($passwordInput)
    ->addElementToNewRow($email)
    ->addElementToNewRow($ouInput)
    ->addElementToNewRow($logonScript)
    ->addElementToNewRow($homeDrive)
    ->addElementToCurrentRow($homePath);
$form3->attachToForm($form);
$form4 = new Form();
$form4->addElementToNewRow($groups)
    ->addElementToCurrentRow($copyFrom);
$form4->attachToForm($form);
//echo $form->print();
?>
<!--<form action="/users/create" method="post" name="UserCreate" id="UserCreateForm">-->

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
    <li class="nav-item">
        <a class="nav-link" id="groups-tab" data-toggle="tab" href="#groups" role="tab" aria-controls="groups"
           aria-selected="false">Groups</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <?php
        echo $form->print();
        ?>
    </div>
    <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
        <?php
        //echo $form2->print();
        ?>
    </div>
    <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="account-tab">
        <?php
        echo $form3->print();
        ?>
    </div>
    <div class="tab-pane fade" id="groups" role="tabpanel" aria-labelledby="groups-tab">
        <?php
        echo $form4->print();
        ?>
    </div>
</div>
<?= $button->print(); ?>
<!--</form>-->


