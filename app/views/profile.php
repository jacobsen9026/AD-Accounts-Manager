<div>
    <h3>
        Profile

    </h3>
</div>

<div>
    <?php
    $profileForm = new \system\app\Form('/profile');
    $profileForm->buildDropDownInput('Theme', 'theme', app\config\Theme::getThemes())
            ->addToRow()
            ->buildSubmitButton('Save Profile', 'success')
            ->addToNewRow();
    echo $profileForm->getFormHTML();
    ?>
    <form action = "/profile" method = "post">
        <select name = "theme">
            <?php
            ?>
        </select>

    </form>
</div>
