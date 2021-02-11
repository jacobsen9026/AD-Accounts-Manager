<?php


use App\Models\View\Javascript;
use App\Forms\FormText;
use System\App\Forms\FormButton;
use System\App\Forms\FormElementGroup;
use System\App\Forms\Form;

$action = new FormText('', '', 'action', 'resetPassword');
$action->small();
$action->hidden();
$username = new FormText('', '', 'username', $params['username']);
$username->small();
$username->hidden();
$newPassword = new FormText('', '', 'password');
$newPassword->setPlaceholder("New password")
    ->large()
    ->isPassword();
$confirmNewPassword = new FormText('', '', 'confirmPassword');
$confirmNewPassword->setPlaceholder("Confirm New password")
    ->large()
    ->isPassword();

$submitButton = new FormButton('<i class="far fa-save"></i>');
$submitButton->setId("setPassword_Button");

$matchingPasswordsCheck = 'if ($("#' . $newPassword->getId() . '").val() != $("#' . $confirmNewPassword->getId() . '").val()) {
                alert("Passwords do not match. ");
                return false;
            }
            
            return true;';
$passwordInputValidation = Javascript::on($submitButton->getId(), $matchingPasswordsCheck);


$submitButton->addInputClasses("h-100")
    ->setScript($passwordInputValidation)
    ->tiny();

$changePasswordGroup = new FormElementGroup();
$changePasswordGroup->addElementToGroup($confirmNewPassword)
    ->addElementToGroup($submitButton);

$form = new Form('', 'setPassword');
$form->addElementToCurrentRow($action)
    ->addElementToNewRow($username)
    ->addElementToNewRow($newPassword)
    ->addElementToNewRow($changePasswordGroup);
echo $form->print();