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
echo $form->getFormHTML();
?>

