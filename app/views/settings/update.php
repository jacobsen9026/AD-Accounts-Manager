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

use App\App\AppUpdater;
use App\Models\View\Modal;
use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\Core;

$updater = new AppUpdater();
$updater->setCheckSSL(false);
$availableVersion = 'Running the latest version!';
$latestVersion = '';
if ($updater->isUpdateAvailable()) {
    $latestVersion = $updater->getLatestVersion();
    $availableVersion = 'Verison: ' . $latestVersion . ' is available.';
}
echo $availableVersion;
$updateForm = new Form('/update', 'updateApp');
$updateButton = new \System\App\Forms\FormButton('Update to v' . $latestVersion);
$updateButton->tiny();
$updateForm->addElementToCurrentRow($updateButton);

$modalBody = Core::getVersion() . ' -> ' . $latestVersion . '<br>' . $updateForm->print();

$updateModal = new Modal();
$updateModal->setTitle('Update App')
    ->setBody($modalBody)
    ->small()
    ->setId('update_app_modal');

$updateModalButton = new FormButton('Update App');
$updateModalButton->addModal($updateModal)
    ->tiny();
echo $updateModalButton->print();
?>

