<?php

use system\app\Form;
use system\Lang;

$form = new Form('/');
$form->buildTextInput(Lang::get('Username'), 'username')
        ->medium()
        ->addToRow(1)
        ->buildPasswordInput(Lang::get('Password'), 'password')
        ->medium()
        ->addToRow(2)
        ->buildSubmitButton(Lang::get('Login'), 'primary')
        ->addToRow(3);
echo $form->getFormHTML();
?>

