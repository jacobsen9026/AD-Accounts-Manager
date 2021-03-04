<?php

use App\App\App;
use App\Forms\FormText;

use App\Models\View\Toast;
use System\App\Forms\Form;
use System\App\Forms\FormButton;

use System\Get;
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
$action = App::get()->request->getUri();
if (!Get::get('redirect') == false) {
    $action .= '?redirect=' . Get::get('redirect');
}
$form = new Form($action);
$form->setName('loginForm');
$username = new FormText('', '', 'username');
$username->setPlaceholder(Lang::get("Username"));

$password = new FormText('', '', 'password');
$password->isPassword()
    ->setPlaceholder(Lang::get("Password"));
if (isset($params['username'])) {
    $username->setValue($params['username']);
    $password->autofocus();
} else {

    $username->autofocus();
}
$loginButton = new FormButton(Lang::get("Login"));
$loginButton->setTheme('secondary');
$form->addElementToNewRow($username)
    ->addElementToNewRow($password)
    ->addElementToNewRow($loginButton);
echo $form->print();
?>

