

<?php

use app\database\Schema;

echo $this->view('layouts/setup_navbar');
?>
<div class="p-5">
    <?php
//var_dump($this->school);
    ?>
    <h4>Editing Team <?php echo $this->team[Schema::TEAM_NAME[Schema::COLUMN]]; ?> at <?= $this->school[Schema::SCHOOL_NAME[Schema::COLUMN]] ?></h4>


    <form method = "post" action = "/teams/edit/<?php echo $this->team[Schema::TEAM_ID[Schema::COLUMN]]; ?>">
        <?php
//formTextInput('Name', Schema::TEAMS_NAME, $this->team[Schema::TEAMS_NAME]);
        ?>

        <div class="form-group mt-5">
            <?php
            // formTextInput('Staff Google Apps OU', Schema::TEAMS_STAFF_GA_OU, $this->team[Schema::TEAMS_STAFF_GA_OU]);
            // formTextInput('Staff Active Directory OU', Schema::TEAMS_STAFF_AD_OU, $this->team[Schema::TEAMS_STAFF_AD_OU]);
            // formTextInput('Staff Active Directory Group', Schema::TEAMS_STAFF_AD_GROUP, $this->team[Schema::TEAMS_STAFF_AD_GROUP]);
            // formTextInput('Staff Google Apps Group', Schema::TEAMS_STAFF_GA_GROUP, $this->team[Schema::TEAMS_STAFF_GA_GROUP]);
            ?>
        </div>


        <div class="form-group mt-5">

            <?php
            //formTextInput('Student Google Apps OU', Schema::TEAMS_STUDENT_GA_OU, $this->team[Schema::TEAMS_STUDENT_GA_OU]);
            //formTextInput('Student Active Directory OU', Schema::TEAMS_STUDENT_AD_OU, $this->team[Schema::TEAMS_STUDENT_AD_OU]);
            // formTextInput('Student Google Group', Schema::TEAMS_STUDENT_GA_GROUP, $this->team[Schema::TEAMS_STUDENT_GA_GROUP]);
            //  formTextInput('Student Active Directory Group', Schema::TEAMS_STUDENT_AD_GROUP, $this->team[Schema::TEAMS_STUDENT_AD_GROUP]);
            //formBinaryInput('Force Password Changes', Schema::TEAMS_FORCE_STUDENT_PASSWORD_CHANGE, $this->team[Schema::TEAMS_FORCE_STUDENT_PASSWORD_CHANGE]);
            ?>
        </div>



        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>


