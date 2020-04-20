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
use app\models\AppConfig;
use app\models\Auth;
use system\app\forms\Form;
use app\auth\LDAP;

$this->auth = Auth::get();
//var_dump($this->auth);
//var_dump($this->auth[Schema::AUTH_LDAP_ENABLED[Schema::COLUMN]]);

$server = $this->auth[Schema::AUTH_LDAP_SERVER[Schema::COLUMN]];
$username = $this->auth[Schema::AUTH_LDAP_USERNAME[Schema::COLUMN]];
$password = $this->auth[Schema::AUTH_LDAP_PASSWORD[Schema::COLUMN]];
if (!empty($server)) {
    $adTestResult = LDAP::testConnection($server, $username, $password);
}
//var_dump($adTestResult);
$form = new Form(null, 'authentication');

$form->buildTextInput('Session Timeout',
                Schema::AUTH_SESSION_TIMEOUT,
                Auth::getSessionTimeout(),
                'The length of time a session can remain idle in seconds',
                '1200')
        ->small()
        ->addToNewRow()
        ->buildPasswordInput('Admin Password',
                Schema::APP_ADMIN_PASSWORD,
                AppConfig::getAdminPassword(),
                'Set a new admin password')
        ->addToRow();


$form->addSeperator();

$form->buildBinaryInput('LDAP Enabled',
                Schema::AUTH_LDAP_ENABLED,
                Auth::getLDAPEnabled(),
                'Allow authentication by LDAP')
        ->center()
        ->addToNewRow();
if (Auth::getLDAPEnabled()) {
    $form->buildStatusCheck('LDAP Connection Status',
                    $adTestResult)
            ->addToRow();
}
$form->buildBinaryInput('Use SSL with LDAP',
                Schema::AUTH_LDAP_USE_SSL,
                $this->auth[Schema::AUTH_LDAP_USE_SSL[Schema::COLUMN]],
                'Requires web server setup.')
        ->addToRow()
        ->buildTextInput('LDAP Server',
                Schema::AUTH_LDAP_SERVER,
                $this->auth[Schema::AUTH_LDAP_SERVER[Schema::COLUMN]],
                'Can also be the domain name in most environments.',
                'ldap.constoso.com / ldap / contoso.com')
        ->addToNewRow()
        ->buildTextInput('LDAP FQDN',
                Schema::AUTH_LDAP_FQDN,
                $this->auth[Schema::AUTH_LDAP_FQDN[Schema::COLUMN]],
                'This will be appended to usenames on log on.',
                'contoso.com')
        ->addToRow()
        ->buildTextInput('LDAP Server Port',
                Schema::AUTH_LDAP_PORT,
                $this->auth[Schema::AUTH_LDAP_PORT[Schema::COLUMN]],
                'The port to connect to (389 for ldap, 636 for ldaps)',
                '389')
        ->addToRow()
        ->buildTextInput('LDAP Username',
                Schema::AUTH_LDAP_USERNAME,
                $this->auth[Schema::AUTH_LDAP_USERNAME[Schema::COLUMN]],
                'User for binding (leave blank for annonymous)',
                'annonymous')
        ->addToNewRow()
        ->buildPasswordInput('LDAP Password',
                Schema::AUTH_LDAP_PASSWORD,
                $this->auth[Schema::AUTH_LDAP_PASSWORD[Schema::COLUMN]],
                'Bind user\'s password')
        ->addToRow()
        ->buildTextInput('LDAP Basic User Permission Group',
                Schema::AUTH_BASIC_AD_GROUP,
                $this->auth[Schema::AUTH_BASIC_AD_GROUP[Schema::COLUMN]],
                'Enter group name')
        ->addToNewRow()
        ->buildTextInput('LDAP Power User Permission Group',
                Schema::AUTH_POWER_AD_GROUP,
                $this->auth[Schema::AUTH_POWER_AD_GROUP[Schema::COLUMN]],
                'Enter group name')
        ->addToRow()
        ->buildTextInput('LDAP Admin User Permission Group',
                Schema::AUTH_ADMIN_AD_GROUP,
                $this->auth[Schema::AUTH_ADMIN_AD_GROUP[Schema::COLUMN]],
                'Enter group name')
        ->addToNewRow()
        ->buildTextInput('LDAP Tech User Permission Group',
                Schema::AUTH_TECH_AD_GROUP,
                $this->auth[Schema::AUTH_TECH_AD_GROUP[Schema::COLUMN]],
                'Enter group name')
        ->addToRow()
        /*
          ->addSeperator()
          ->buildBinaryInput('OAuth Enabled',
          Schema::AUTH_OAUTH_ENABLED,
          $this->auth[Schema::AUTH_OAUTH_ENABLED[Schema::COLUMN]],
          'Allow authentication by OAuth2')
          ->disable()
          ->addToNewRow()
          ->buildTextInput('Google Apps Basic User Permission Group',
          Schema::AUTH_BASIC_GA_GROUP,
          $this->auth[Schema::AUTH_BASIC_GA_GROUP[Schema::COLUMN]],
          'Enter group name',
          'SAM Basic Users')
          ->addToNewRow()
          ->buildTextInput('Google Apps Power User Permission Group',
          Schema::AUTH_POWER_GA_GROUP,
          $this->auth[Schema::AUTH_POWER_GA_GROUP[Schema::COLUMN]],
          'Enter group name',
          'SAM Power Users')
          ->addToRow()
          ->buildTextInput('Google Apps Admin User Permission Group',
          Schema::AUTH_ADMIN_GA_GROUP,
          $this->auth[Schema::AUTH_ADMIN_GA_GROUP[Schema::COLUMN]],
          'Enter group name',
          'SAM Admin Users')
          ->addToNewRow()
          ->buildTextInput('Google Apps Tech User Permission Group',
          Schema::AUTH_TECH_GA_GROUP,
          $this->auth[Schema::AUTH_TECH_GA_GROUP[Schema::COLUMN]],
          'Enter group name',
          'SAM Tech Users')
          ->addToRow()
         *
         */
        ->buildUpdateButton()
        ->addToNewRow();
echo $form->getFormHTML();
?>

