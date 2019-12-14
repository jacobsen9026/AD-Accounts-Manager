

<?php

use app\database\Schema;

function formTextInput($label, $name, $value) {
    ?>
    <div class="form-group">
        <label class="font-weight-bold" for="<?= $name; ?>"><?= $label; ?></label>
        <input class="form-control text-center" name="<?= $name; ?>" value="<?= $value; ?>"/>
    </div>
    <?php
}

echo $this->view('settings/district/schools/nav');
?>
<div class="">
    <?php
    //var_dump($this->school);
    ?>
    <h4>Editing Grade <?php echo $this->grade["Level"]; ?> at <?= $this->school[Schema::SCHOOLS_NAME] ?></h4>


    <form method = "post" action = "/grades/edit/<?php echo $this->grade[Schema::GRADES_ID]; ?>">
        <?php
        //formTextInput('Name', Schema::GRADES_NAME, $this->grade[Schema::GRADES_NAME]);
        ?>

        <div class="form-group mt-5">
            <?php
            formTextInput('Staff Google Apps OU', Schema::GRADES_STAFFGAOU, $this->grade[Schema::GRADES_STAFFGAOU]);
            formTextInput('Staff Active Directory OU', Schema::GRADES_STAFFADOU, $this->grade[Schema::GRADES_STAFFADOU]);
            formTextInput('Staff Active Directory Group', Schema::GRADES_STUDENTADGROUP, $this->grade[Schema::GRADES_STUDENTADGROUP]);
            formTextInput('Staff Google Apps Group', Schema::GRADES_STUDENTGAGROUP, $this->grade[Schema::GRADES_STUDENTGAGROUP]);
            ?>
        </div>


        <div class="form-group mt-5">

            <?php
            formTextInput('Student Google Apps OU', Schema::GRADES_STUDENTGAOU, $this->grade[Schema::GRADES_STUDENTGAOU]);
            formTextInput('Student Active Directory OU', Schema::GRADES_STUDENTADOU, $this->grade[Schema::GRADES_STUDENTADOU]);
            formTextInput('Student Google Group', Schema::GRADES_STUDENTGAGROUP, $this->grade[Schema::GRADES_STUDENTGAGROUP]);
            ?>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>


