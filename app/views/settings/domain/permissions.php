<?php
/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
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

use App\Models\Database\PrivilegeLevelDatabase;
use App\Models\View\Javascript;
use App\Models\View\PermissionMapPrinter;
use App\Models\District\Domain;

/* @var $domain Domain */
/** @var Domain $district */
$domain = $this->domain;

$exportButton = new \System\App\Forms\FormButton('Export Permissions');
$exportButton->addClientRequest('/api/settings/export/permissions');


//echo $this->view('layouts/setup_navbar');

$cleanOu = PermissionMapPrinter::cleanOU($domain->getAdBaseDN());
?>
<div id="permissionSettingsContainer">
    <div id="managePrivilegeLevelsContainer" class='shadow p-5 bg-white'>
        <?php

        $showPrivilegeLevelsCommand = Javascript::buildAJAXRequest('/api/settings/domain/permissions', "managePrivilegeLevelsContainer", ['action' => 'getManagePrivilegeLevels'], true);
        echo "<script>" . Javascript::onPageLoad($showPrivilegeLevelsCommand) . "</script>";
        ?>


    </div>

    <?php
    if (PrivilegeLevelDatabase::get() !== false) {
        ?>


        <div id="<?= $cleanOu ?>" class='shadow p-5 mt-5 bg-white'>
            <?php
            $showApplicationLevelPermissionsCommand = Javascript::buildAJAXRequest('/api/settings/domain/permissions', $cleanOu, ['action' => 'getApplicationLevelPermissions'], true);
            echo "<script>" . Javascript::onPageLoad($showApplicationLevelPermissionsCommand) . "</script>";
            ?>

        </div>


        <div id="ouLevelPermissionsContainer" class='shadow p-5 mt-5 mb-5 bg-white '>
            <?php
            $showOuLevelPermissionsCommand = Javascript::buildAJAXRequest('/api/settings/domain/permissions', "ouLevelPermissionsContainer", ['action' => 'getOULevelPermissions'], true);
            echo "<script>" . Javascript::onPageLoad($showOuLevelPermissionsCommand) . "</script>";
            ?>
        </div>
        <div id="exportPermissionsContainer" class='shadow p-5 mt-5 mb-5 bg-white '>
            <?= $exportButton->getElementHTML(); ?>
            <script><?= $exportButton->getScript(); ?></script>

        </div>
        <div class="my-5">
            &nbsp;
        </div>
        <?php
    }
    ?>
</div>