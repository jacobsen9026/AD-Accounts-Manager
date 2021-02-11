<?php

use App\Forms\FormText;

use App\Models\View\Toast;
use System\App\Forms\Form;
use System\App\Forms\FormButton;

use System\Lang;
use System\Request;

if (isset($params['toast'])) {
    echo $params['toast'];

}
if (Request::get()->serverName == 'demo.adam-app.gq') {
    $toastBody = "<strong>To login use</strong><br>demo<br>demo<br><br>";
    $demoToast = new Toast('Demo Account', $toastBody, 10000);
    $demoToast->closable();
    echo $demoToast->printToast();
}
$form = new Form();
$username = new FormText('', '', 'username');
$username->autofocus()
    ->setPlaceholder(Lang::get("Username"));
$password = new FormText('', '', 'password');
$password->isPassword()
    ->setPlaceholder(Lang::get("Password"));
$loginButton = new FormButton(Lang::get("Login"));
$loginButton->setTheme('secondary');
$form->addElementToNewRow($username)
    ->addElementToNewRow($password)
    ->addElementToNewRow($loginButton);
echo $form->print();
?>

