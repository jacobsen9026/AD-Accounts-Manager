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
use App\Models\Database\EmailTemplateDatabase;
use App\Models\View\Modal;
use App\Models\View\Toast;
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

//$staffBlind = new FormTextArea('', $subLabel, $name)
$save = new FormFloatingButton('<i class="h3 mb-0 fas fa-check"></i>');
$save->setId('floatingSaveButton')
    ->addAJAXRequest('/api/settings/notification', 'settingsOutput', $form);


$currentTemplates = EmailTemplateDatabase::get();
//var_dump($currentTemplates);


$newEmailTemplateForm = new Form('/settings/notification/create', 'emailTemplates', 'get');

$updateEmailTemplateForm = new Form('/settings/notification/update', 'updateTemplate');

$testEmailTemplateForm = new Form('/settings/notification/test', 'testTemplate');


$deleteEmailTemplateForm = new Form('/settings/notification/delete', 'deleteTemplate');


$newEmailTemplateForm->setOnSubmit('console.log("submit");');
$emailTemplateDropdown = new FormDropdown('Email Templates', '', 'emailTemplatesDropdown');
$newTemplateName = new FormText('Template Name', '', 'template_name');

$saveNewTemplate = new FormButton('Create', 'small');
$createTemplateGroup = new FormElementGroup('New Template Name', 'Create a new email template', '');
$createTemplateGroup->addElementToGroup($newTemplateName)
    ->addElementToGroup($saveNewTemplate);
$newEmailTemplateForm->addElementToCurrentRow($createTemplateGroup);
$createTemplateButton = new FormButton('Create a new template');
$createTemplateButton->buildModal('Create a new template', $newEmailTemplateForm->print());
if ($currentTemplates !== false) {
    foreach ($currentTemplates as $template) {
        $jsonTemplates[$template["ID"]] = $template;
        $option = new FormDropdownOption($template["Name"], $template["ID"]);
        $emailTemplateDropdown->addOption($option);
    }
    $emailTemplateName = new FormText('Name', '', 'name', $currentTemplates[0]["Name"]);

    $emailTemplateID = new FormText('', '', 'id', $currentTemplates[0]["ID"]);
    $emailTemplateID->hidden();
    $emailTemplateCC = new FormTextArea('', '', 'cc', $currentTemplates[0]["CC"]);
    $emailCCGroup = new FormElementGroup('CC', 'One email per line', '');
    $emailCCGroup->addElementToGroup($emailTemplateCC->addElementClasses('w-100'))
        ->medium();

    $emailTemplateBCC = new FormTextArea('', '', 'bcc', $currentTemplates[0]["BCC"]);
    $emailBCCGroup = new FormElementGroup('BCC', 'One email per line', '');
    $emailBCCGroup->addElementToGroup($emailTemplateBCC->addElementClasses('w-100'))
        ->medium();

    $emailTemplateSubject = new FormText('Subject', '', 'subject', $currentTemplates[0]["Subject"]);
    $emailTemplateSubject->medium();
    $emailTemplateBody = new FormTextArea('', '', 'body',
        $currentTemplates[0]["Body"]);
    $emailTemplateBody->resizable()
        ->addElementClasses('w-100')
        ->addInputClasses('text-small');

    $bodyHelpText = "Variables:<br>{{FULL_NAME}}<br>{{USERNAME}}<br>{{EMAIL_ADDRESS}}<br>";

    $emailBodyGroup = new FormElementGroup('Body', $bodyHelpText, '');
    $emailBodyGroup->addElementToGroup($emailTemplateBody)
        ->full();

    $updateEmailTemplate = new FormButton('Update');
    // $updateEmailTemplate->addAJAXRequest('/api/settings/notification/update', null, $updateEmailTemplateForm);

    $emailBodyPreview = new FormHTML('');
    $emailBodyPreview->full();
    $emailBodyPreview->setHtml('<div id="previewCollapse" class="collapse"><iframe class="w-100"  style="height:80vh" id="emailPreview"  src="about:blank""></iframe></div>');
    $emailPreviewModal = new Modal();
    $emailPreviewModal->setBody('')
        ->setTitle('Email Preview')
        ->setId('emailPreviewModal')
        ->addBodyClass("p-0")
        ->extraLarge();
    $emailPreviewButton = new FormButton('Show template preview');
    $emailPreviewButton->setDataToggle("collapse")
        ->setDataTarget("#previewCollapse")
        ->setType('button')
        ->setDataTextAlt('Hide template preview');

    $sendTemplateTo = new FormText('Send test to', '', 'template_to');
    $sendTemplateTest = new FormButton('Send Test Email', 'small');
    $sendTemplateTest->addAJAXRequest('/api/settings/email/sendTestTemplate', null, $updateEmailTemplateForm);
    $testEmailTemplateForm->addElementToNewRow($sendTemplateTo)
        ->addElementToCurrentRow($sendTemplateTest);


    $updateEmailTemplateForm->addElementToNewRow($emailTemplateDropdown)
        ->addElementToNewRow($emailTemplateName)
        ->addElementToCurrentRow($emailCCGroup)
        ->addElementToNewRow($emailBCCGroup)
        ->addElementToCurrentRow($emailTemplateID)
        ->addElementToCurrentRow($emailTemplateSubject)
        ->addElementToNewRow($emailBodyGroup)
        ->addElementToNewRow($emailPreviewButton)
        ->addElementToNewRow($emailBodyPreview)
        ->addElementToNewRow($updateEmailTemplate)
        ->addElementToNewRow($sendTemplateTo)
        ->addElementToCurrentRow($sendTemplateTest);


    $deleteButton = new FormButton('Delete');
    $deleteID = new FormText('', '', 'deleteID', $currentTemplates[0]["ID"]);;
    $deleteID->hidden();
    $deleteEmailTemplateForm->addElementToNewRow($deleteID)
        ->addElementToNewRow($deleteButton);
}


$newEmailTemplateForm->setActionVariable($newTemplateName);

$unsavedChangesWarningToast = new Toast('Unsaved changes', 'You are about to lose changes you\'ve made', 3000);
$unsavedChangesWarningToast->setId("unsavedChangesToast")
    ->startHidden();
$changesLostToast = new Toast('Changes were not saved', 'You\'re previous changes were not saved.', 3000);

$changesLostToast->setId("changesLostToast")
    ->startHidden();

?>
<script>

    history.pushState(null, 'Notification', '/settings/notification');

    var timeout = null;
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

    function interpretAndPrintPreview(body) {
        if (timeout != undefined && timeout != null) {
            clearTimeout(timeout);
            timeout = null;
        }
        timeout = setTimeout(function () {
            $.post("/api/settings/notification/interpret", {
                    body: body,
                    csrfToken: '<?=Form::getCsrfToken()?>'
                }, function (data) {
                    data = JSON.parse(data);
                    $('#emailPreview').attr('src', 'data:text/html;charset=utf-8,' + escape(data.output.ajax.html));
                    //$('#emailPreview').html(data.output.ajax.html);
                }
            );
            timeout = null;
        }, 1000);

    }

    var body = $('#bodyInputArea').val();
    interpretAndPrintPreview(body);
    var stopChangeTemplate = function (e) {
        if (e.target.localName == "select") {
            try {

                console.log('unsaved');
                // If you prevent default behavior in Mozilla Firefox prompt will always be shown
                // Chrome requires returnValue to be set
                $('#unsavedChangesToast').toast('show');
            } catch {

            }

            return false;
        }
    };
    var templateChanged = function (e) {
        if (e.target.localName == "select") {
            console.log("select changed");
            window.removeEventListener('click', stopChangeTemplate);
            window.removeEventListener('beforeunload', stopUnload);

            window.removeEventListener('change', templateChanged);
            $('#changesLostToast').toast('show');

        }
    };
    //templates = JSON.parse(templates);
    console.log(templates);
    $('#bodyInputArea').on('keyup', function (e) {
        console.log("body edited");
        console.log($('#bodyInputArea').val());
        var body = $('#bodyInputArea').val();
        interpretAndPrintPreview(body);

    });
    $('#bodyInputArea').on('keydown', function (e) {
        if (e.key == "s" && e.ctrlKey) {
            $('#<?=$updateEmailTemplate->getId()?>').click();
            e.preventDefault();
            return false;
        }

    });
    $('select').on('change', function (e) {
        var selectedID = ($('select').find(":selected").val());

        $('#deleteIDInput').val(selectedID);
        $('#idInput').val(selectedID);
        $('#nameInput').val(templates[selectedID].Name);
        $('#subjectInput').val(templates[selectedID].Subject);
        $('#bodyInputArea').val(templates[selectedID].Body);
        $('#ccInputArea').val(templates[selectedID].CC);
        $('#bccInputArea').val(templates[selectedID].BCC);
        $('#emailPreview').src = templates[selectedID].Body;
    })
    $('#nameInput, #bodyInputArea, #subjectInput').on('change', function (e) {
        console.log('changes made');
        changesMade = true;

        window.addEventListener('beforeunload', stopUnload);
        window.addEventListener('click', stopChangeTemplate);
        window.addEventListener('change', templateChanged);

    });

    $('#Update').on("click", function () {
        window.removeEventListener('beforeunload', stopUnload);
    });

</script>
<?php echo $unsavedChangesWarningToast->printToast();
echo $changesLostToast->printToast(); ?>

<?= $createTemplateButton->print(); ?>



<?= $updateEmailTemplateForm->print(); ?>

<?= $deleteEmailTemplateForm->print(); ?>





