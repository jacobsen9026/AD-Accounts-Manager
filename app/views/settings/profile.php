<?php

use system\app\forms\Form;
use system\app\forms\FormText;
use system\app\forms\FormDropdown;
use system\app\forms\FormDropdownOption;
use system\app\forms\FormButton;
?>
<div>
    <h3>
        Profile

    </h3>
</div>

<div>
    <?php
    $profileForm = new Form('/settings/profile');
    $profileDropDown = new FormDropdown("Theme", "theme");
    foreach (app\config\Theme::getThemes()as $theme) {
        $option = new FormDropdownOption($theme, $theme);


        if ($theme == $this->user->theme) {
            $option->selected();
        }
        $profileDropDown->addOption($option);
    }
    $apiKey = new FormText('API Key', '', 'apiKey', $this->user->getApiToken());
    $apiKey->disable()
            ->setId('apiKey');
    $saveButton = new FormButton("Save Profile");
    $privilegeLevel = new FormText("Privilege", "Maximum is " . app\models\user\Privilege::TECH);
    $privilegeLevel->setValue($this->user->privilege)
            ->disable();

    $updateAPIKey = new FormButton("Update API Key");
    $updateAPIKey->addAJAXRequest('/api/user/newAPIKey', 'apiKey')
            ->setType("button")
            ->small()
            ->setSubLabel("Updating will overwrite existing API key. You're old key will no longer work.");
    $profileForm->addElementToNewRow($profileDropDown)
            ->addElementToNewRow($privilegeLevel)
            ->addElementToNewRow($apiKey)
            ->addElementToNewRow($updateAPIKey)
            ->addElementToNewRow($saveButton);
    echo $profileForm->print();
    ?>

</div>
