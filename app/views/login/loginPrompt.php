<?php

use System\App\Forms\Form;
use System\App\Forms\FormText;
use System\App\Forms\FormButton;

use System\Lang;

if (isset($params['toast'])) {
    echo $params['toast'];

}
if (\System\Request::get()->serverName == 'demo.adam-app.gq') {
    $toastBody = "<strong>To login use</strong><br>demo<br>demo<br><br>";
    $demoToast = new \App\Models\View\Toast('Demo Account', $toastBody, 10000);
    $demoToast->closable();
    echo $demoToast->printToast();
}
$form = new Form();
$username = new FormText(Lang::get("Username"), '', 'username');
$username->autofocus();
$password = new FormText(Lang::get("Password"), '', 'password');
$password->isPassword();
$loginButton = new FormButton(Lang::get("Login"));
$loginButton->setTheme('secondary');
$form->addElementToNewRow($username)
    ->addElementToNewRow($password)
    ->addElementToNewRow($loginButton);
echo $form->print();
?>

