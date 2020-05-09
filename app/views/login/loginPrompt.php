<?php

use system\app\forms\Form;
use system\Lang;

$form = new Form();
if (isset($this->lastErrorMessage)) {
    $form->buildErrorOutput($this->lastErrorMessage)
            ->addToNewRow();
}

$form->buildTextInput(Lang::get('Username'), 'username')
        ->medium()
        ->addToNewRow()
        ->buildPasswordInput(Lang::get('Password'), 'password')
        ->medium()
        ->addToNewRow()
        ->buildSubmitButton(Lang::get('Login'), 'primary')
        ->addToNewRow();
//echo $form->getFormHTML();
$form = new Form();
$username = new \system\app\forms\FormText(Lang::get("Username"), '', 'username');
$password = new \system\app\forms\FormText(Lang::get("Password"), '', 'password');
$password->isPassword();
$loginButton = new \system\app\forms\FormButton(Lang::get("Login"));
$loginButton->setTheme('secondary');
$form->addElementToNewRow($username)
        ->addElementToNewRow($password)
        ->addElementToNewRow($loginButton);
echo $form->print();
?>

