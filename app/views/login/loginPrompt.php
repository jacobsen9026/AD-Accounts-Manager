<?php

use System\App\Forms\Form;
use System\App\Forms\FormText;
use System\App\Forms\FormButton;

use System\Lang;

if (isset($params['toast'])) {
    echo $params['toast'];

}

$form = new Form();
$username = new FormText(Lang::get("Username"), '', 'username');
$password = new FormText(Lang::get("Password"), '', 'password');
$password->isPassword();
$loginButton = new FormButton(Lang::get("Login"));
$loginButton->setTheme('secondary');
$form->addElementToNewRow($username)
    ->addElementToNewRow($password)
    ->addElementToNewRow($loginButton);
echo $form->print();
?>

