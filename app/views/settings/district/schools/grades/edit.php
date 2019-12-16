

<?php

use app\database\Schema;

echo $this->view('settings/district/schools/nav');
?>
<div class="p-5">
    <?php
//var_dump($this->school);
    ?>
    <h4 class="mb-5">Editing Grade <?php echo $this->grade["Level"]; ?> at <?= $this->school[Schema::SCHOOLS_NAME] ?></h4>


    <form method = "post" action = "/grades/edit/<?php echo $this->grade[Schema::GRADES_ID]; ?>">
        <?php
//formTextInput('Name', Schema::GRADES_NAME, $this->grade[Schema::GRADES_NAME]);
        ?>

        <div class="row">
            <div class="col-lg">
                <?php
                formTextInput('Staff Google Apps OU', Schema::GRADES_STAFF_GA_OU, $this->grade[Schema::GRADES_STAFF_GA_OU]);
                formTextInput('Staff Active Directory OU', Schema::GRADES_STAFF_AD_OU, $this->grade[Schema::GRADES_STAFF_AD_OU]);
                formTextInput('Staff Active Directory Group', Schema::GRADES_STAFF_AD_GROUP, $this->grade[Schema::GRADES_STAFF_AD_GROUP]);
                formTextInput('Staff Google Apps Group', Schema::GRADES_STAFF_GA_GROUP, $this->grade[Schema::GRADES_STAFF_GA_GROUP]);
                ?>
            </div>
            <div class="col-lg">

                <?php
                formTextInput('Student Google Apps OU', Schema::GRADES_STUDENT_GA_OU, $this->grade[Schema::GRADES_STUDENT_GA_OU]);
                formTextInput('Student Active Directory OU', Schema::GRADES_STUDENT_AD_OU, $this->grade[Schema::GRADES_STUDENT_AD_OU]);
                formTextInput('Student Google Group', Schema::GRADES_STUDENT_GA_GROUP, $this->grade[Schema::GRADES_STUDENT_GA_GROUP]);
                formTextInput('Student Active Directory Group', Schema::GRADES_STUDENT_AD_GROUP, $this->grade[Schema::GRADES_STUDENT_AD_GROUP]);
                ?>
            </div>
        </div>
        <?php
        formBinaryInput('Force Password Changes', Schema::GRADES_FORCE_STUDENT_PASSWORD_CHANGE, $this->grade[Schema::GRADES_FORCE_STUDENT_PASSWORD_CHANGE]);
        ?>
        <a href="/teams/show/<?php echo $this->grade[Schema::GRADES_ID]; ?>" class="my-3 btn btn-warning">Edit Teams (Optional)</a><br/>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>


