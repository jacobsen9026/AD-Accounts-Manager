

<?php

use app\database\Schema;

echo $this->view('settings/district/schools/nav');
?>
<div class="p-5">
    <?php
    //var_dump($this->school);
    ?>
    <head>
        <title>Editing <?php echo $this->school[Schema::SCHOOLS_NAME]; ?></title>
    </head>
    <h4>Editing <?php echo $this->school[Schema::SCHOOLS_NAME]; ?></h4>


    <form method="post" action="/schools/edit/<?php echo $this->school[Schema::SCHOOLS_ID]; ?>">

        <?php
        formTextInput('Name', Schema::SCHOOLS_NAME, $this->school[Schema::SCHOOLS_NAME]);
        ?>
        <div class="row">
            <div class="col-lg">
                <?php
                formTextInput('Staff Google Apps OU', Schema::SCHOOLS_STAFF_GA_OU, $this->school[Schema::SCHOOLS_STAFF_GA_OU]);
                formTextInput('Staff Active Directory OU', Schema::SCHOOLS_STAFF_AD_OU, $this->school[Schema::SCHOOLS_STAFF_AD_OU]);
                formTextInput('Staff Google Apps Group', Schema::SCHOOLS_STAFF_GA_GROUP, $this->school[Schema::SCHOOLS_STAFF_GA_GROUP]);
                ?>
            </div>
            <div class="col-lg">
                <?php
                formTextInput('Staff Active Directory Group', Schema::SCHOOLS_STAFF_AD_GROUP, $this->school[Schema::SCHOOLS_STAFF_AD_GROUP]);
                formTextInput('Other Staff Email Group', Schema::SCHOOLS_OTHER_STAFF_EMAIL_GROUPS, $this->school[Schema::SCHOOLS_OTHER_STAFF_EMAIL_GROUPS]);
                ?>
            </div>
        </div>
        <a href="/grades/show/<?php echo $this->school[Schema::SCHOOLS_ID]; ?>" class="my-3 btn btn-warning">Edit Grades</a><br/>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>


