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

use App\Controllers\Api\App;
use App\Models\View\Javascript;
use System\Lang;

//$form = new Form();
$showApplicationSettingsCommand = Javascript::buildAJAXRequest('/api/app/appSettings', "settingsOutput");
$showAuthenticationSettingsCommand = Javascript::buildAJAXRequest('/api/app/authSettings', "settingsOutput");
$showEmailSettingsCommand = Javascript::buildAJAXRequest('/api/app/emailSettings', "settingsOutput");
$showNotificationSettingsCommand = Javascript::buildAJAXRequest('/api/app/notifSettings', "settingsOutput");
$showUpdateSettingsCommand = Javascript::buildAJAXRequest('/api/app/update', "settingsOutput");
$showBackupSettingsCommand = Javascript::buildAJAXRequest('/api/app/backup', "settingsOutput");
if (!isset($this->tab) or $this->tab == null) {
    $this->tab = "application";
}
switch ($this->tab) {
    case 'application':
        $goto = '#nav-app-tab';
        break;
    case 'authentication':
        $goto = '#nav-auth-tab';
        break;
    default:
        $goto = '#nav-' . $this->tab . '-tab';
        break;
}
?>
<script>
    //Highlight changed items on all forms
    $(document).ready(function () {
        $('.straight-loader.hidden').removeClass('hidden');
        $('<?= $goto ?>').click();


        $('select').change(function () {

            $(this).addClass('border-danger text-danger');

        });

        $(document).on("ajaxSend", function () {
            $('.straight-loader.hidden').removeClass('hidden');
        });
        $(document).on("ajaxComplete ", function () {
            $('.straight-loader').addClass('hidden');
        });

    });
</script>
<h4 class="centered text-center">
    Settings
</h4>
<nav>
    <div class="nav nav-tabs nav-fill nav-justified" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="nav-app-tab" data-toggle="tab" href="#nav-app" role="tab"
           aria-controls="nav-app" aria-selected="true"
           onclick='<?= $showApplicationSettingsCommand ?>'><?= Lang::get("Application") ?></a>
        <a class="nav-item nav-link" id="nav-auth-tab" data-toggle="tab" href="#nav-auth" role="tab"
           aria-controls="nav-auth" aria-selected="false"
           onclick='<?= $showAuthenticationSettingsCommand ?>'><?= Lang::get("Authentication") ?></a>
        <a class="nav-item nav-link" id="nav-email-tab" data-toggle="tab" href="#nav-email" role="tab"
           aria-controls="nav-email" aria-selected="false"
           onclick='<?= $showEmailSettingsCommand ?>'><?= Lang::get("Email") ?></a>
        <a class="nav-item nav-link" id="nav-notification-tab" data-toggle="tab" href="#nav-notification" role="tab"
           aria-controls="nav-notification" aria-selected="false"
           onclick='<?= $showNotificationSettingsCommand ?>'><?= Lang::get("Notification") ?></a>
        <a class="nav-item nav-link" id="nav-update-tab" data-toggle="tab" href="#nav-update" role="tab"
           aria-controls="nav-update" aria-selected="false"
           onclick='<?= $showUpdateSettingsCommand ?>'><?= Lang::get("Update") ?></a>
        <a class="nav-item nav-link" id="nav-backup-tab" data-toggle="tab" href="#nav-backup" role="tab"
           aria-controls="nav-backup" aria-selected="false"
           onclick='<?= $showBackupSettingsCommand ?>'><?= Lang::get("Backup") ?></a>
    </div>
</nav>
<div class="row"></div>

<?php echo Javascript::$hiddenSpinner ?>

<div class="col pt-4" id="settingsOutput"></div>

