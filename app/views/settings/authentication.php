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

use app\models\database\AuthDatabase;
use system\app\forms\Form;
use app\auth\ADAuth;
use system\app\forms\FormText;

$auth = new AuthDatabase();

$server = $auth->getLDAPServer();
$username = $auth->getLDAPUsername();
$password = $auth->getLDAPPassword();
if (!empty($server) and $auth->getLDAPEnabled()) {
    $adTestResult = ADAuth::testConnection($server, $username, $password);
}



$form = new Form('/settings/authentication', "authentication");
$sessionTimeout = new FormText("Session Timeout", "The length of time a session can remain idle in seconds", "sessionTimeout", $auth->getSessionTimeout());
$adminPassword = new FormText("Admin Password", "Set a new admin password", "adminPassword", $auth->getAdminPassword());
$adminPassword->isPassword();
$ldapEnabled = new system\app\forms\FormRadio("AD Logon Enabled", "Allow logon with Active Directory accounts", "ldapEnabled");
$ldapEnabled->addOption("False", 0, !$auth->getLDAPEnabled())
        ->addOption("True", 1, $auth->getLDAPEnabled());
$ldapSSL = new system\app\forms\FormRadio("Use SSL with AD", "Requires web server configuration", "ldapSSL");
$ldapSSL->addOption("False", 0, !$auth->getLDAPUseSSL())
        ->addOption("True", 1, $auth->getLDAPUseSSL());
$ldapServerName = new FormText("LDAP Server", "Can also be the domain name in most environments.", "ldapServer", $auth->getLDAPServer());
$ldapFQDN = new FormText("LDAP FQDN", "This will be appended to usenames on log on.", "ldapFQDN", $auth->getLDAP_FQDN());
$ldapPort = new FormText("LDAP Server Port", "The port to connect to (389 for ldap, 636 for ldaps)", "ldapPort", $auth->getLDAP_Port());
$ldapUsername = new FormText("LDAP Username", "User for authentication binding (leave blank for annonymous)", "ldapUsername", $auth->getLDAPUsername());
$ldapPassword = new FormText("LDAP Password", "Bind user's password", "ldapPassword", $auth->getLDAPPassword());
$ldapBasicGroup = new FormText("LDAP Basic User Permission Group", null, "ldapBasic", $auth->getBasicADGroup());
$ldapPowerGroup = new FormText("LDAP Power User Permission Group", null, "ldapPower", $auth->getPowerADGroup());
$ldapAdminGroup = new FormText("LDAP Admin User Permission Group", null, "ldapAdmin", $auth->getAdminADGroup());
$ldapTechGroup = new FormText("Active Directory Super User Group", null, "ldapTech", $auth->getSuperUserADGroup());
$ldapTechGroup->medium();
$button = new \system\app\forms\FormButton("Save");
$button->small()->addAJAXRequest('/api/settings/authentication', 'settingsOutput', $form);


$form->addElementToNewRow($sessionTimeout)
        ->addElementToCurrentRow($adminPassword)
        ->addElementToNewRow($ldapEnabled)
        ->addElementToCurrentRow($ldapSSL)
        ->addElementToNewRow($ldapTechGroup)
        /**
          ->addElementToNewRow($ldapServerName)
          ->addElementToCurrentRow($ldapFQDN)
          ->addElementToCurrentRow($ldapPort)
          ->addElementToNewRow($ldapUsername)
          ->addElementToCurrentRow($ldapPassword)
          /**
          ->addElementToNewRow($ldapBasicGroup)
          ->addElementToCurrentRow($ldapPowerGroup)
          ->addElementToNewRow($ldapAdminGroup)
         *
         */
        ->addElementToNewRow($button);
echo $form->print();
?>
