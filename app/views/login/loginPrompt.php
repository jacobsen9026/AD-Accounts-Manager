<?php

use System\App\Forms\Form;
use System\Lang;

$form = new Form();
if (isset($this->lastErrorMessage)) {
    $form->buildErrorOutput($this->lastErrorMessage)
            ->addToNewRow();
}

$form = new Form();
$username = new \System\App\Forms\FormText(Lang::get("Username"), '', 'username');
$password = new \System\App\Forms\FormText(Lang::get("Password"), '', 'password');
$password->isPassword();
$loginButton = new \System\App\Forms\FormButton(Lang::get("Login"));
$loginButton->setTheme('secondary');
$form->addElementToNewRow($username)
        ->addElementToNewRow($password)
        ->addElementToNewRow($loginButton);
echo $form->print();
?>

