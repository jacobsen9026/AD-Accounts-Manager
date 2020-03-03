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
use app\models\Email;
use system\app\forms\Form;

$this->email = Email::get();
//var_dump($this->email);



$form = new Form(null, 'email');

$form->buildTextInput('SMTP Server',
                Schema::EMAIL_SMTP_SERVER,
                $this->email[Schema::EMAIL_SMTP_SERVER[Schema::COLUMN]],
                'Read only user for authentication',
                'samAuthUser')
        ->addToRow()
        ->buildTextInput('SMTP Port',
                Schema::EMAIL_SMTP_PORT,
                $this->email[Schema::EMAIL_SMTP_PORT[Schema::COLUMN]],
                'Allow authentication by LDAP')
        ->center()
        ->addToRow()
        ->buildBinaryInput('Use SMTP Auth',
                Schema::EMAIL_USE_SMTP_AUTH,
                $this->email[Schema::EMAIL_USE_SMTP_AUTH[Schema::COLUMN]],
                '')
        ->addToNewRow()
        ->buildTextInput('SMTP Username',
                Schema::EMAIL_SMTP_USERNAME,
                $this->email[Schema::EMAIL_SMTP_USERNAME[Schema::COLUMN]],
                'Can also be the domain name in most environments.',
                'ldap.constoso.com')
        ->addToRow()
        ->buildTextInput('SMTP Password',
                Schema::EMAIL_SMTP_PASSWORD,
                $this->email[Schema::EMAIL_SMTP_PASSWORD[Schema::COLUMN]],
                'Non-SSL:389  SSL:',
                '389')
        ->addToNewRow()
        ->buildBinaryInput('Use SMTP over SSL',
                Schema::EMAIL_USE_SMTP_SSL,
                $this->email[Schema::EMAIL_USE_SMTP_SSL[Schema::COLUMN]],
                'Read only user for authentication')
        ->addToRow()
        ->buildPasswordInput('From Address',
                Schema::EMAIL_FROM_ADDRESS,
                $this->email[Schema::EMAIL_FROM_ADDRESS[Schema::COLUMN]],
                'Read only user\'s password')
        ->addToNewRow()
        ->buildTextInput('From Name',
                Schema::EMAIL_FROM_NAME,
                $this->email[Schema::EMAIL_FROM_NAME[Schema::COLUMN]],
                'Read only user for authentication',
                AppConfig::getAppName())
        ->addToRow()
        ->buildPasswordInput('Reply-To Address',
                Schema::EMAIL_REPLY_TO_ADDRESS,
                $this->email[Schema::EMAIL_REPLY_TO_ADDRESS[Schema::COLUMN]],
                'Read only user\'s password')
        ->addToNewRow()
        ->buildTextInput('Reply-To Name',
                Schema::EMAIL_REPLY_TO_NAME,
                $this->email[Schema::EMAIL_REPLY_TO_NAME[Schema::COLUMN]],
                'Read only user for authentication',
                AppConfig::getAppName())
        ->addToRow()
        ->buildUpdateButton()
        ->addToNewRow();
echo $form->getFormHTML();
?>

