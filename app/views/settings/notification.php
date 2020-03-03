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

$form->buildTextAreaInput('Admin Email Addresses',
                Schema::EMAIL_ADMIN_EMAIL_ADDRESSES,
                $this->email[Schema::EMAIL_ADMIN_EMAIL_ADDRESSES[Schema::COLUMN]],
                'Read only user for authentication',
                'samAuthUser')
        ->addToRow()
        ->buildTextAreaInput('Staff Welcome Email Blind Recipients',
                Schema::EMAIL_WELCOME_EMAIL_BCC,
                $this->email[Schema::EMAIL_WELCOME_EMAIL_BCC[Schema::COLUMN]],
                'Allow authentication by LDAP')
        ->center()
        ->addToRow()
        ->buildTextAreaInput('Staff Welcome Email',
                Schema::EMAIL_WELCOME_EMAIL,
                $this->email[Schema::EMAIL_WELCOME_EMAIL[Schema::COLUMN]],
                'Allow authentication by LDAP')
        ->addToNewRow()
        ->buildUpdateButton()
        ->addToNewRow();
echo $form->getFormHTML();
?>

