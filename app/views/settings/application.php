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

use App\Models\Database\AppDatabase;
use App\Models\View\Javascript;
use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\App\Forms\FormFloatingButton;
use System\App\Forms\FormSlider;
use App\Forms\FormText;
use System\App\Forms\FormTextArea;
use System\App\Forms\FormUpload;
use System\Post;


$form = new Form('/settings/application', 'application');


$webAppName = new FormText("Web App Name", null, "webAppName", AppDatabase::getAppName());
$webAppName->large();


$webFQDN = new FormText("Website FQDN", "If this is set all requests are redirected to this address. Be sure it is correct and stays available.", "webAppFQDN", AppDatabase::getWebsiteFQDN());
$webFQDN->large()
    ->setPlaceholder("Enter the public FQDN that users use to access this applicaiton.");

$uploadPhoto = new FormUpload('', '', 'app_icon');
$uploadPhoto->setBrowseButtonText('<i class="fas fa-img"></i>');
$uploadPhoto->tiny()
    //->addAJAXRequest('/api/settings/application', 'settingsOutput', $form)
    ->hidden();
$uploadPhotoButton = new FormButton('Upload App Icon');
$uploadPhotoButton->setType('button');
$uploadPhotoButton->setId('upload_photo_button')
    ->tiny()
    ->setTooltip("Max size: " . Post::getMaxUploadSize());
$clickUpload = '$("#' . $uploadPhoto->getId() . '").click()';
$function = Javascript::on($uploadPhotoButton->getId(), $clickUpload);
$uploadPhotoButton->setScript($function);

$webHelpDesk = new FormText("User Helpdesk URL", "The url that users should use to access your help portal.", "webHelpdeskURL", AppDatabase::getUserHelpdeskURL());
$webHelpDesk->large()
    ->setPlaceholder("https://helpdesk.company.com");


$forceHTTPS = new FormSlider("Force HTTPS", "Redirect all HTTP requests to HTTPS", "forceHTTPS", AppDatabase::getForceHTTPS());
$forceHTTPS->addOption("False", '0', !AppDatabase::getForceHTTPS());
$forceHTTPS->addOption("True", '1', AppDatabase::getForceHTTPS());


$homepageMessage = new FormTextArea();
$homepageMessage->setLabel("Homepage Message")
    ->setSubLabel("Accepts HTML and inline style")
    ->setName("webMOTD")
    ->setValue(AppDatabase::getMOTD());

$submitButton = new FormFloatingButton('<i class="h3 mb-0 fas fa-check"></i>');
$submitButton->setId('floatingSaveButton')
    ->addAJAXRequest('/api/settings/application', 'settingsOutput', $form);


$form->addElementToNewRow($webAppName)
    ->addElementToCurrentRow($uploadPhoto)
    ->addElementToCurrentRow($uploadPhotoButton)
    ->addElementToNewRow($homepageMessage)
    ->addElementToNewRow($webFQDN)
    ->addElementToNewRow($forceHTTPS)
    ->addElementToNewRow($webHelpDesk)
    ->addElementToNewRow($submitButton);
echo $form->print();
?>
<script>
    if (window.location.pathname != '/settings/application') {
        history.pushState(null, 'Notification', '/settings/application');
    }
</script>







