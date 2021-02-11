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
use System\App\Forms\Form;
use System\App\Forms\FormFloatingButton;
use System\App\Forms\FormTextArea;

$this->email = EmailDatabase::get();
//var_dump($this->email);

$form = new Form('', 'notification');
$adminEmails = new FormTextArea('Admin Email Addresses', 'Recieves important system notifications', 'adminEmails');
//$staffBlind = new FormTextArea('', $subLabel, $name)
$save = new FormFloatingButton('<i class="h3 mb-0 fas fa-check"></i>');
$save->setId('floatingSaveButton')
    ->addAJAXRequest('/api/settings/notification', 'settingsOutput', $form);
$form->addElementToCurrentRow($adminEmails)
    ->addElementToNewRow($save);
echo $form->print();
?>

