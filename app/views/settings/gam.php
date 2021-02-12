<?php
/*
 * The MIT License
 *
 * Copyright 2019 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

use app\database\Schema;
use App\Models\Database\EmailDatabase;

$this->email = EmailDatabase::get();
//var_dump($this->email);
$clientSecretExists = \App\Api\GAM::get()->clientSecretExists();
$google = \App\Api\GAM::get();
//var_dump($google);
if (!$clientSecretExists) {


    $jsonInstructions = 'If you do not have a json you will need to make/download it from the '
        . '<a href="https://console.developers.google.com/apis/dashboard">Google Developers Console</a>'
        . '<br/>You can create a new project or add an Oauth credential to an existing one.';
    $form = new Form('/settings/application', 'gam');
    $form->buildFileInput("client_secret",
        'Upload a client_secret.json',
        'Choose your client_secret.json',
        'application/JSON')
        ->addToRow()
        ->addToForm($jsonInstructions)
        //->buildCustomButton('Check OAuth Validity', 'primary', 'api/gam/test')
        //->addOnClickListenerFunction($onClick)
        // ->addToNewRow()
        ->buildUpdateButton()
        ->addToNewRow();
    echo $form->getFormHTML();
}
?>
<?php if (!$google->isAuthorized() and $google->clientSecretExists()): ?>
    <a class='btn btn-primary login' href='<?= $google->getAuthUrl() ?>'>Authorize this app!</a>
<?php endif ?>

<?php if ($google->isAuthorized()): ?>
    You are connected to <?= $google->getDomainNames()[0] ?><br>
    <a class='btn btn-primary login' href='<?= $google->getAuthUrl() ?>'>Authorize this app!</a>

    <br>
    Scopes<br>

    <?php
    foreach ($google->getScopes() as $scope) {
        echo $scope . "<br>";
    }
endif ?>
