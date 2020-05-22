<?php

use System\App\Forms\Form;
use System\App\Forms\FormText;
use System\App\Forms\FormDropdown;
use System\App\Forms\FormDropdownOption;
use System\App\Forms\FormButton;
use App\Models\View\Javascript;
use App\Models\User\User;

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

    $apiKey = new FormText('API Key', '', 'apiKey', $user->getApiToken());
    $apiKey->disable()
        ->setId('apiKey')
        //->medium()
        ->setScript(Javascript::onClick('apiKey', Javascript::copyToClipboard('apiKey')));
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
        //     ->addElementToNewRow($privilegeLevel)
        ->addElementToNewRow($apiKey)
        ->addElementToNewRow($updateAPIKey)
        ->addElementToNewRow($saveButton);
    echo $profileForm->print();
    ?>

</div>
