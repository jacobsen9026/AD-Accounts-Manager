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

use App\App\App;
use App\App\AppUpdater;
use App\Models\View\Javascript;
use App\Models\View\Modal;
use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\App\Forms\FormSlider;
use System\App\Forms\FormText;
use System\Core;

$updater = new AppUpdater();
$availableVersion = 'Running the latest version!';
$latestVersion = '';
if ($updater->isUpdateAvailable()) {
    $latestVersion = $updater->getLatestVersion();
    $availableVersion = 'Verison: ' . $latestVersion . ' is available.';
}
/**
 *  Form Button that opens a modal with a form in it
 */
$updateForm = new Form('/settings/update', 'updateApp');


$updateModal = new Modal();
$updateModal->setTitle('Update App')
    ->small()
    ->setId('update_app_modal');


$simulationSlider = new FormSlider('Simulation Mode', '', 'simulationMode', 0);
$simulationSlider->addOption('Real Update', 0, true)
    ->addOption('Simulation', 1, false);

if (!App::get()->inDebugMode()) {
    $simulationSlider->hidden();
}

$action = new FormText('', '', 'action', 'updateApp');
$action->hidden();


$updateButton = new FormButton('Update to v' . $latestVersion);
$updateButton->tiny()
    ->addElementClasses('mt-5')
    ->addAJAXRequest('/api/update', 'settingsOutput', $updateForm);

$updateForm->addElementToCurrentRow($simulationSlider)
    ->addElementToNewRow($action)
    ->addElementToNewRow($updateButton);

/**
 * Now that the update form has been made we can continue the modal
 */
$closeModalFunction = '$(\'#' . $updateModal->getId() . '\').modal(\'hide\')';
$closeModalFunction = Javascript::on($updateButton->getId(), $closeModalFunction);


$updateButton->setScript($closeModalFunction);

$modalBody = 'Current Version: ' . Core::getVersion() . '<br><br>' . $updateForm->print();
$updateModal->setBody($modalBody);

$updateModalButton = new FormButton('Update App');
$updateModalButton->addModal($updateModal)
    ->tiny();

?>

<div class="w-auto d-inline-flex font-weight-bold p-3">
    <?php echo $availableVersion; ?></div>

<?php
if ($availableVersion !== 'Running the latest version!') {
    echo $updateModalButton->print();
}
?>

