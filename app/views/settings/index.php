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

use System\App\Forms\Form;
use App\Controllers\Api\App;
use App\Models\View\Javascript;

//$form = new Form();
$showApplicationSettingsCommand = Javascript::buildAJAXRequest('/api/app', "settingsOutput", ['action' => App::GET_APP_SETTINGS]);
$showAuthenticationSettingsCommand = Javascript::buildAJAXRequest('/api/app', "settingsOutput", ['action' => App::GET_AUTH_SETTINGS]);
$showEmailSettingsCommand = Javascript::buildAJAXRequest('/api/app', "settingsOutput", ['action' => App::GET_EMAIL_SETTINGS]);
$showNotificationSettingsCommand = Javascript::buildAJAXRequest('/api/app', "settingsOutput", ['action' => App::GET_NOTIF_SETTINGS]);
$showUpdateSettingsCommand = Javascript::buildAJAXRequest('/api/app', "settingsOutput", ['action' => App::GET_UPDATE_SETTINGS]);
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
    case 'email':
        $goto = '#nav-email-tab';

        break;
    case 'notification':
        $goto = '#nav-notification-tab';
        break;
    case 'update':
        $goto = '#nav-update-tab';
        break;
    default:
        $goto = '#nav-app-tab';
        break;
}
?>
<script>
    //Highlight changed items on all forms
    $(document).ready(function () {

        $('<?= $goto ?>').click();
        $('input').keyup(function () {
            $(this).addClass('text-danger border-danger');

        });

        $('select').change(function () {

            $(this).addClass('border-danger text-danger');

        });

    });
</script>
<h4 class="centered text-center">
    Settings
</h4>
<nav>
    <div class="nav nav-tabs nav-fill nav-justified" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="nav-app-tab" data-toggle="tab" href="#nav-app" role="tab"
           aria-controls="nav-app" aria-selected="true" onclick='<?= $showApplicationSettingsCommand ?>'>Application</a>
        <a class="nav-item nav-link" id="nav-auth-tab" data-toggle="tab" href="#nav-auth" role="tab"
           aria-controls="nav-auth" aria-selected="false" onclick='<?= $showAuthenticationSettingsCommand ?>'>Authentication</a>
        <a class="nav-item nav-link" id="nav-email-tab" data-toggle="tab" href="#nav-email" role="tab"
           aria-controls="nav-email" aria-selected="false" onclick='<?= $showEmailSettingsCommand ?>'>Email</a>
        <a class="nav-item nav-link" id="nav-notification-tab" data-toggle="tab" href="#nav-notification" role="tab"
           aria-controls="nav-notification" aria-selected="false" onclick='<?= $showNotificationSettingsCommand ?>'>Notification</a>
        <a class="nav-item nav-link" id="nav-update-tab" data-toggle="tab" href="#nav-update" role="tab"
           aria-controls="nav-update" aria-selected="false" onclick='<?= $showUpdateSettingsCommand ?>'>Update</a>
    </div>
</nav>
<div class="row"></div>
<div class="col pt-4" id="settingsOutput"></div>

