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
use system\app\forms\Form;
use system\app\forms\FormButton;
use system\app\forms\FormText;

$form = new Form('/settings/application', 'authentication');
$form->buildTextInput('Web App Name', Schema::APP_NAME, AppConfig::getAppName())
        ->addToRow()
        ->buildTextAreaInput('Admin Usernames',
                Schema::APP_PROTECTED_ADMIN_USERNAMES,
                AppConfig::getAdminUsernames(),
                'Users in this list will not be able to be modified via the tools on this site.',
                'Enter one username per line')
        ->addToNewRow()
        ->buildTextAreaInput('Homepage Message',
                Schema::APP_MOTD,
                AppConfig::getMOTD(),
                'Accepts HTML and inline style',
                'Enter a message to display to all users.')
        ->addToNewRow()
        ->buildTextInput('Website FQDN',
                Schema::APP_WEBSITIE_FQDN,
                AppConfig::getWebsiteFQDN(),
                'If this is set all requests are redirected to this address. Be sure it is correct and stays available.',
                'Enter the public FQDN that users use to access this application.')
        ->addToNewRow()
        ->buildBinaryInput('Force HTTPS',
                Schema::APP_FORCE_HTTPS,
                AppConfig::getForceHTTPS(),
                'Reditect all HTTP requests to HTTPS')
        ->addToNewRow()
        ->buildBinaryInput('Debug Mode',
                Schema::APP_DEBUG_MODE,
                AppConfig::getDebugMode(),
                'Caution: Enabling debug mode is a security risk and should only be used on development systems.')
        ->addToRow()
        ->buildTextInput('User Helpdesk URL',
                Schema::APP_USER_HELPDESK_URL,
                AppConfig::getUserHelpdeskURL(),
                'The url that users should use to access your help portal.',
                'https://helpdesk.com/')
        ->addToNewRow()
        ->buildUpdateButton('Save Settings')
        ->addToNewRow();
echo $form->getFormHTML();

$form = new Form('/settings/application', 'authentication');
$button = new FormButton("Submit");
$button->small();
$webAppName = new FormText("Web App Name",null,Schema::APP_NAME, AppConfig::getAppName());
$webAppName->large();
$webFQDN = new FormText("Website FQDN","If this is set all requests are redirected to this address. Be sure it is correct and stays available.",Schema::APP_WEBSITIE_FQDN, AppConfig::getWebsiteFQDN());
$webFQDN->large()
        ->setPlaceholder("Enter the public FQDN that users use to access this applicaiton.");
$webHelpDesk = new FormText("User Helpdesk URL","The url that users should use to access your help portal.",Schema::APP_USER_HELPDESK_URL, AppConfig::getUserHelpdeskURL());
$webHelpDesk->large()
        ->setPlaceholder("https://helpdesk.company.com");
//$adminUsernames = new FormTextArea ();
$findGroups= new FormText("Find Groups Test", "Search for groups", "group");
$findGroups->autoCompleteGroupName();

$form->addElementToNewRow($webAppName)
        ->addElementToNewRow($webFQDN)
        ->addElementToNewRow($webHelpDesk)
        ->addElementToNewRow($findGroups)
        ->addElementToNewRow($button);
echo $form->print();

?>

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
?>






