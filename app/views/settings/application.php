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
?>


<form method="post" class ="table-hover">
    <?php
    $function = function() {
        if (isset($appConfig["redirectHTTP"])) {
            if ($appConfig["redirectHTTP"]) {
                if (strtolower(explode("=", $_SERVER['HTTPS_SERVER_ISSUER'])[1]) == strtolower($_SERVER['SERVER_NAME'])) {
                    echo "You are using a self-signed certificate.<br/>Understand the risks of allowing this<br/>to be published on the internet.";
                }
            } else {
                echo "You are not using HTTPS.<br/>Understand the risks of allowing this<br/>to be published on the internet.";
            }
        } else {
            echo "You are not using HTTPS.<br/>Understand the risks of allowing this<br/>to be published on the internet.";
        }
    };

    formTextInput('Web App Name', Schema::APP_NAME, AppConfig::getAppName());
    formTextArea('Admin Usernames', Schema::APP_PROTECTED_ADMIN_USERNAMES, AppConfig::getAdminUsernames(),
            'Users in this list will not be able to be modified via the tools on this site.', 'Enter one username per line');

    formTextArea('Homepage Message', Schema::APP_MOTD, AppConfig::getMOTD(),
            'Accepts HTML and inline style', 'Enter list of emails, one per line.');
    ?>
    <div class="row">
        <div class="col-md">
            <?php formBinaryInput('Redirect to HTTPS', Schema::APP_FORCE_HTTPS, AppConfig::getForceHTTPS(), null, $function); ?>
        </div>
        <div class="col-md">
            <?php
            formBinaryInput('App Debug Mode', Schema::APP_DEBUG_MODE, AppConfig::getDebugMode(),
                    'Only for developement or error reporting');
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md">
            <?php
            formTextInput('Session Timeout', Schema::AUTH_SESSION_TIMEOUT, Auth::getSessionTimeout(),
                    'Time in seconds');
            ?>
        </div>
        <div class="col-md">
            <?php
            formTextInput('Website Public FQDN', Schema::APP_WEBSITIE_FQDN, AppConfig::getWebsiteFQDN(),
                    'All requests will be redirected to this address. Ensure it is accurate.');
            ?>
        </div>
    </div>
    <?php
    formTextInput('User Helpdesk URL', Schema::APP_USER_HELPDESK_URL, AppConfig::getUserHelpdeskURL(),
            'Provide your users with a link to your support portal for when they need assistance.');
    ?>







    <div  class="container pt-5"><button type="submit" class="mx-auth btn btn-primary mb-2">Save Settings</button></div>
</form>