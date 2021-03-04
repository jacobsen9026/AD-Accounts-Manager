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

use App\Forms\FormText;
use App\Models\Database\EmailDatabase;
use App\Models\Database\NotificationDatabase;
use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\App\Forms\FormDropdown;
use System\App\Forms\FormDropdownOption;
use System\App\Forms\FormElementGroup;
use System\App\Forms\FormFloatingButton;
use System\App\Forms\FormHTML;
use System\App\Forms\FormTextArea;


$this->email = EmailDatabase::get();

$form = new Form('', 'notification');
$adminEmails = new FormTextArea('Admin Email Addresses', 'Recieves important system notifications', 'adminEmails', EmailDatabase::getAdminEmailAddresses());

//$staffBlind = new FormTextArea('', $subLabel, $name)
$save = new FormFloatingButton('<i class="h3 mb-0 fas fa-check"></i>');
$save->setId('floatingSaveButton')
    ->addAJAXRequest('/api/settings/notification', 'settingsOutput', $form);
$form->addElementToCurrentRow($adminEmails);
echo $form->print();


$currentTemplates = NotificationDatabase::get();
//var_dump($currentTemplates);


$newEmailTemplateForm = new Form('/settings/notification/create', 'emailTemplates', 'get');

$updateEmailTemplateForm = new Form('/settings/notification/update', 'updateTemplate');

$deleteEmailTemplateForm = new Form('/settings/notification/delete', 'deleteTemplate');


$newEmailTemplateForm->setOnSubmit('console.log("submit");');
$emailTemplateDropdown = new FormDropdown('Email Templates', '', 'emailTemplatesDropdown');
$newTemplateName = new FormText('Template Name', '', 'template_name');

$saveNewTemplate = new FormButton('Create', 'small');
$createTemplateGroup = new FormElementGroup('New Template Name', 'Create a new email template', '');
$createTemplateGroup->addElementToGroup($newTemplateName)
    ->addElementToGroup($saveNewTemplate);
$newEmailTemplateForm->addElementToCurrentRow($createTemplateGroup);

if ($currentTemplates !== false) {
    foreach ($currentTemplates as $template) {
        $jsonTemplates[$template["ID"]] = $template;
        $option = new FormDropdownOption($template["Name"], $template["ID"]);
        $emailTemplateDropdown->addOption($option);
    }
    $emailTemplateName = new FormText('Name', '', 'name', $currentTemplates[0]["Name"]);

    $emailTemplateID = new FormText('', '', 'id', $currentTemplates[0]["ID"]);
    $emailTemplateID->hidden();
    $emailTemplateSubject = new FormText('Subject', '', 'subject', $currentTemplates[0]["Subject"]);
    $emailTemplateSubject->medium();
    $emailTemplateBody = new FormTextArea('', '', 'body', $currentTemplates[0]["Body"]);
    $emailTemplateBody->resizable()
        ->addElementClasses('w-100');
    $emailBodyGroup = new FormElementGroup('Body', '', '');
    $emailBodyGroup->addElementToGroup($emailTemplateBody);
    $updateEmailTemplate = new FormButton('Update');
    $emailBodyPreview = new FormHTML('Preview');
    $emailBodyPreview->full();
    $emailBodyPreview->setHtml('<iframe class="w-100"  style="height:100vw" id="emailPreview"  src="about:blank""></iframe>');
    $updateEmailTemplateForm->addElementToNewRow($emailTemplateDropdown)
        ->addElementToCurrentRow($emailTemplateName)
        ->addElementToNewRow($updateEmailTemplate)
        ->addElementToNewRow($emailTemplateID)
        ->addElementToNewRow($emailTemplateSubject)
        ->addElementToCurrentRow($emailBodyGroup)
        ->addElementToNewRow($emailBodyPreview)
        ->addElementToNewRow($updateEmailTemplate);


    $deleteButton = new FormButton('Delete');
    $deleteID = new FormText('', '', 'deleteID', $currentTemplates[0]["ID"]);;
    $deleteID->hidden();
    $deleteEmailTemplateForm->addElementToNewRow($deleteID)
        ->addElementToNewRow($deleteButton);
}


$newEmailTemplateForm->setActionVariable($newTemplateName);


?>
<script>

    history.pushState(null, 'Notification', '/settings/notification');
    $('#emailPreview').attr('src', 'data:text/html;charset=utf-8,' + escape($('#bodyInputArea').val()));
    var changesMade = false;
    var templates = <?=json_encode($jsonTemplates)?>;
    var stopUnload = function (e) {
        try {

            console.log('unsaved');
            // If you prevent default behavior in Mozilla Firefox prompt will always be shown
            // Chrome requires returnValue to be set

            e.preventDefault();
            e.returnValue = '';
        } catch {

        }

        return false;
    };
    //templates = JSON.parse(templates);
    console.log(templates);
    $('#bodyInputArea').on('keyup', function () {
        console.log("body edited");
        console.log($('#bodyInputArea').val());
        $('#emailPreview').attr('src', 'data:text/html;charset=utf-8,' + escape($('#bodyInputArea').val()));
    });

    $('select').on('change', function (e) {
        var selectedID = ($('select').find(":selected").val());

        $('#deleteIDInput').val(selectedID);
        $('#idInput').val(selectedID);
        $('#nameInput').val(templates[selectedID].Name);
        $('#subjectInput').val(templates[selectedID].Subject);
        $('#bodyInputArea').val(templates[selectedID].Body);
        $('#emailPreview').src = templates[selectedID].Body;
    })
    $('#nameInput, #bodyInputArea, #subjectInput').on('change', function (e) {
        console.log('changes made');
        changesMade = true;

        window.addEventListener('beforeunload', stopUnload);

    });
    $('#Update').on("click", function () {
        window.removeEventListener('beforeunload', stopUnload);
    });

</script>
<div class="card shadow">
    <div class="card-header">Create a new template
        <button class="right border-0 bg-transparent text-success mr-2"
                data-toggle="collapse"
                data-target="#newTemplateCard"
                data-text-alt='<i class="fa fa-minus"></i>'><i class="fa fa-plus"></i></button>
    </div>
    <div id="newTemplateCard" class="card-body collapse">
        <?= $newEmailTemplateForm->print(); ?>
    </div>
</div>


<div class="mt-5 card shadow">
    <div class="card-header">Edit existing templates
        <button class="right border-0 bg-transparent text-success mr-2"
                data-toggle="collapse"
                data-target="#editTemplateCard"
                data-text-alt='<i class="fa fa-minus"></i>'><i class="fa fa-plus"></i></button>
    </div>
    <div id="editTemplateCard" class="card-body collapse">
        <?= $updateEmailTemplateForm->print(); ?>

        <?= $deleteEmailTemplateForm->print(); ?>
    </div>
</div>




