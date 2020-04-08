<div>
    <h3>
        Profile

    </h3>
</div>

<div>
    <?php
    $profileForm = new \system\app\forms\Form('/settings/profile');
    $profileDropDown = new \system\app\forms\FormDropdown("Theme", "theme");
    foreach (app\config\Theme::getThemes()as $theme) {
        $option = new system\app\forms\FormDropdownOption($theme, $theme);


        if ($theme == $this->user->theme) {
            $option->selected();
        }
        $profileDropDown->addOption($option);
    }

    $saveButton = new \system\app\forms\FormButton("Save Profile");
    $profileForm->addElementToNewRow($profileDropDown)
            ->addElementToNewRow($saveButton);
    echo $profileForm->print();
    ?>

</div>
