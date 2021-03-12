<?php

use App\Forms\FormText;
use System\App\Forms\Form;
use System\App\Forms\FormDropdown;
use System\App\Forms\FormDropdownOption;
use System\App\Forms\FormButton;
use App\Models\View\Javascript;
use App\Models\User\User;
use System\App\Forms\FormSlider;

?>
<div>
    <h3>
        Profile

    </h3>
</div>

<div>
    <?php
    /* @var $user User */
    $user = $this->user;
    $profileForm = new Form('/settings/profile');
    $themeDropDown = new FormDropdown("Theme", 'Set display theme', "theme");
    //$themeDropDown->medium();
    foreach (app\config\Theme::getThemes() as $theme) {
        $option = new FormDropdownOption($theme, $theme);


        if ($theme == $user->theme) {
            $option->selected();
        }
        $themeDropDown->addOption($option);
    }
    $userEmail = new FormText('Email Address', '', 'email', $user->getEmail());
    $notifyUserChange = new FormSlider('Notify on user changes', '', 'notify_user_changes', (int)$user->getNotificationOptions()->isUserChange());
    $notifyUserChange->small();
    $notifyUserDisable = new FormSlider('Notify on user enable/disable', '', 'notify_user_enable', (int)$user->getNotificationOptions()->isUserDisable());
    $notifyUserDisable->small();
    $notifyUserCreation = new FormSlider('Notify on user creation', '', 'notify_user_create', (int)$user->getNotificationOptions()->isUserCreate());
    $notifyUserCreation->small();
    $notifyGroupChange = new FormSlider('Notify on group changes', '', 'notify_group_change', (int)$user->getNotificationOptions()->isGroupChange());
    $notifyGroupChange->small();
    $notifyGroupCreation = new FormSlider('Notify on group creation', '', 'notify_group_create', (int)$user->getNotificationOptions()->isGroupCreate());
    $notifyGroupCreation->small();
    $apiKey = new FormText('API Key', '', 'apiKey', $user->getApiToken());
    $apiKey->disable()
        ->setId('apiKey')
        //->medium()
        ->setScript(Javascript::on('apiKey', Javascript::copyToClipboard('apiKey')));
    $saveButton = new FormButton("Save Profile");
    $saveButton->large();
    //$privilegeLevel = new FormText("Privilege");
    //$privilegeLevel->setValue(var_export($user->getPrivilegeLevels(), true))
    //       ->disable();
    //->medium();

    $updateAPIKey = new FormButton("Update API Key");
    $updateAPIKey->addAJAXRequest('/api/user/newAPIKey', 'apiKey', $profileForm, false, 'val')
        ->setType("button")
        // ->medium()
        ->setSubLabel("Updating will overwrite existing API key. You're old key will no longer work.");

    $profileForm->addElementToNewRow($themeDropDown)
        ->addElementToNewRow($userEmail)
        ->addElementToNewRow($notifyUserChange)
        ->addElementToCurrentRow($notifyUserDisable)
        ->addElementToCurrentRow($notifyUserCreation)
        ->addElementToCurrentRow($notifyGroupChange)
        ->addElementToCurrentRow($notifyGroupCreation)
        ->addElementToNewRow($apiKey)
        ->addElementToNewRow($updateAPIKey)
        ->addElementToNewRow($saveButton);
    echo $profileForm->print();
    ?>

</div>
