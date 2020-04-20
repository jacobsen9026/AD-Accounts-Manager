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
                'Enter the SMTP server address',
                '')
        ->addToRow()
        ->buildTextInput('SMTP Port',
                Schema::EMAIL_SMTP_PORT,
                $this->email[Schema::EMAIL_SMTP_PORT[Schema::COLUMN]],
                'Enter the SMTP server port')
        ->center()
        ->addToRow()
        ->buildBinaryInput('Use SMTP Auth',
                Schema::EMAIL_USE_SMTP_AUTH,
                $this->email[Schema::EMAIL_USE_SMTP_AUTH[Schema::COLUMN]],
                'Use SMTP User Authentication')
        ->addToNewRow()
        ->buildTextInput('SMTP Username',
                Schema::EMAIL_SMTP_USERNAME,
                $this->email[Schema::EMAIL_SMTP_USERNAME[Schema::COLUMN]],
                'SMTP Auth Username',
                '')
        ->addToRow()
        ->buildTextInput('SMTP Password',
                Schema::EMAIL_SMTP_PASSWORD,
                $this->email[Schema::EMAIL_SMTP_PASSWORD[Schema::COLUMN]],
                'SMTP Auth Password',
                '')
        ->addToNewRow()
        ->buildBinaryInput('Use SMTP over SSL',
                Schema::EMAIL_USE_SMTP_SSL,
                $this->email[Schema::EMAIL_USE_SMTP_SSL[Schema::COLUMN]],
                'Send Emails securly')
        ->addToRow()
        ->buildTextInput('From Address',
                Schema::EMAIL_FROM_ADDRESS,
                $this->email[Schema::EMAIL_FROM_ADDRESS[Schema::COLUMN]],
                'The from address for emails sent from this app')
        ->addToNewRow()
        ->buildTextInput('From Name',
                Schema::EMAIL_FROM_NAME,
                $this->email[Schema::EMAIL_FROM_NAME[Schema::COLUMN]],
                'The display name for emails sent from this app',
                AppConfig::getAppName())
        ->addToRow()
        ->buildPasswordInput('Reply-To Address',
                Schema::EMAIL_REPLY_TO_ADDRESS,
                $this->email[Schema::EMAIL_REPLY_TO_ADDRESS[Schema::COLUMN]],
                'The reply-to address for emails sent from this app')
        ->addToNewRow()
        ->buildTextInput('Reply-To Name',
                Schema::EMAIL_REPLY_TO_NAME,
                $this->email[Schema::EMAIL_REPLY_TO_NAME[Schema::COLUMN]],
                'The reply-to display name for emails sent from this app')
        ->addToRow()
        ->buildUpdateButton()
        ->addToNewRow();
echo $form->getFormHTML();
//\system\app\Email::sendTest();
?>

