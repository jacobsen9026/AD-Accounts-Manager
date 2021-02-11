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

use App\Models\Database\AuthDatabase;
use System\App\Forms\Form;
use System\App\Forms\FormFloatingButton;
use App\Forms\FormText;
use App\Api\Ad\ADConnection;

$auth = new AuthDatabase();

if ($auth->getLDAPEnabled()) {
    $ldapConnected = ADConnection::isConnected();
}


$form = new Form('/settings/authentication', "authentication");
$sessionTimeout = new FormText("Session Timeout", "The length of time a session can remain idle in seconds", "sessionTimeout", $auth->getSessionTimeout());
$sessionTimeout->small();
$adminPassword = new FormText("Admin Password", "Set a new admin password", "adminPassword", $auth->getAdminPassword());
$adminPassword->isPassword();


$isLDAPEnabled = $auth->getLDAPEnabled();
$ldapEnabled = new System\App\Forms\FormSlider("AD Logon Enabled", "Allow logon with Active Directory accounts", "ldapEnabled", $isLDAPEnabled);


$ldapEnabled->addOption("False", 0, !$isLDAPEnabled)
    ->addOption("True", 1, $isLDAPEnabled);
if (!$ldapConnected) {
    $ldapEnabled->disable();
}


$saveButton = new FormFloatingButton('<i class="h3 mb-0 fas fa-check"></i>');
$saveButton->setId('floatingSaveButton')
    ->addAJAXRequest('/api/settings/authentication', 'settingsOutput', $form);


$form->addElementToNewRow($sessionTimeout)
    ->addElementToCurrentRow($adminPassword)
    ->addElementToNewRow($ldapEnabled)
    ->addElementToNewRow($saveButton);
echo $form->print();
?>
