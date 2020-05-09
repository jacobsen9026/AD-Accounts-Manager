<?php

use system\app\forms\Form;
use system\app\forms\FormText;
use system\app\forms\FormDropdown;
use system\app\forms\FormDropdownOption;
use system\app\forms\FormButton;
use app\models\view\Javascript;
?>
<div>
    <h3>
        Profile

    </h3>
</div>

<div>
    <?php
    $profileForm = new Form('/settings/profile');
    $themeDropDown = new FormDropdown("Theme", 'Set display theme', "theme");
    //$themeDropDown->medium();
    foreach (app\config\Theme::getThemes()as $theme) {
        $option = new FormDropdownOption($theme, $theme);


        if ($theme == $this->user->theme) {
            $option->selected();
        }
        $themeDropDown->addOption($option);
    }

    $apiKey = new FormText('API Key', '', 'apiKey', $this->user->getApiToken());
    $apiKey->disable()
            ->setId('apiKey')
            //->medium()
            ->setScript(Javascript::onClick('apiKey', Javascript::copyToClipboard('apiKey')));
    $saveButton = new FormButton("Save Profile");
    $saveButton->large();
    $privilegeLevel = new FormText("Privilege", "Maximum is " . app\models\user\Privilege::TECH);
    $privilegeLevel->setValue($this->user->privilege)
            ->disable();
    //->medium();

    $updateAPIKey = new FormButton("Update API Key");
    $updateAPIKey->addAJAXRequest('/api/user/newAPIKey', 'apiKey', $profileForm, false, 'val')
            ->setType("button")
            // ->medium()
            ->setSubLabel("Updating will overwrite existing API key. You're old key will no longer work.");

    $profileForm->addElementToNewRow($themeDropDown)
            ->addElementToNewRow($privilegeLevel)
            ->addElementToNewRow($apiKey)
            ->addElementToNewRow($updateAPIKey)
            ->addElementToNewRow($saveButton);
    echo $profileForm->print();
    ?>

</div>
