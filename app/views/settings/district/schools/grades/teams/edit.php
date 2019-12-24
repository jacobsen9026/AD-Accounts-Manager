

<?php

use app\database\Schema;
use system\app\Form;
use app\models\district\ActiveDirectory;
use app\models\district\GoogleApps;

echo $this->view('layouts/setup_navbar');
?>
<div class="p-5">
    <?php
//var_dump($this->school);
    ?>
    <h4>Editing Team <?php echo $this->team[Schema::TEAM_NAME[Schema::COLUMN]]; ?> at <?= $this->school[Schema::SCHOOL_NAME[Schema::COLUMN]] ?></h4>

    <?php
//var_dump($this->district);
    $form = new Form('/teams/edit/' . $this->team[Schema::GRADE_ID[Schema::COLUMN]]);
    $form->buildTextInput('Parent Email Group', Schema::GRADE_PARENT_EMAIL_GROUP[Schema::NAME], $this->team[Schema::SCHOOL_PARENT_EMAIL_GROUP[Schema::COLUMN]])
            ->appendInput('@' . $this->district[Schema::DISTRICT_GA_FQDN[Schema::COLUMN]])
            ->addToRow(3);


    $form->buildUpdateButton()->addToRow(100);

    echo $form->getFormHTML();
    ?>
    <button class="my-1 btn btn-success collapse show ad_ga_settings"  type="button" data-toggle="collapse" data-target=".ad_ga_settings" aria-expanded="false" aria-controls="collapseExample">
        Show More Settings
    </button>
    <div class="collapse ad_ga_settings" id="ad_ga_settings">

        <nav class=" nav-fill nav-justified">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="rounded-0 shadow bg-primary text-light nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-staff-ad" role="tab" aria-controls="nav-home" aria-selected="true">Staff Active Directory</a>
                <a class="rounded-0 shadow bg-primary text-light nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-staff-ga" role="tab" aria-controls="nav-profile" aria-selected="false">Staff Google Apps</a>
                <a class="rounded-0 shadow bg-primary text-light nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-student-ad" role="tab" aria-controls="nav-home" aria-selected="true">Student Active Directory</a>
                <a class="rounded-0 shadow bg-primary text-light nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-student-ga" role="tab" aria-controls="nav-profile" aria-selected="false">Student Google Apps</a>

            </div>
        </nav>
        <div class="shadow bg-light tab-content py-3 px-md-3" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-staff-ad" role="tabpanel" aria-labelledby="nav-staff-tab">
                <?php
                var_dump(ActiveDirectory::getField(Schema::GRADE, $this->teamID, Schema::ACTIVEDIRECTORY_OU, 'Staff'));
                $staffADForm = new Form('/teams/editStaff/' . $this->team[Schema::GRADE_ID[Schema::COLUMN]]);
                $staffADForm->generateADForm($this->teamID, $this->staffADSettings, Schema::TEAM)
                        ->buildUpdateButton()->addToRow(100);
                echo $staffADForm->getFormHTML();
                ?>
            </div>
            <div class="tab-pane fade" id="nav-staff-ga" role="tabpanel" aria-labelledby="nav-student-tab">
                <?php
                $staffGAForm = new Form('/teams/editStaff/' . $this->team[Schema::GRADE_ID[Schema::COLUMN]]);
                $staffGAForm->generateGAForm($this->teamID, $this->staffGASettings, Schema::TEAM)
                        ->buildUpdateButton('Update GA')
                        ->addToNewRow();

                echo $staffGAForm->getFormHTML();
                ?>
            </div>
            <div class="tab-pane fade" id="nav-student-ad" role="tabpanel" aria-labelledby="nav-staff-tab">
                <?php
                $studentADForm = new Form('/teams/editStudents/' . $this->team[Schema::GRADE_ID[Schema::COLUMN]]);
                $studentADForm->generateADForm($this->teamID, $this->studentADSettings, Schema::TEAM, 'Student')
                        ->buildUpdateButton('Update AD')
                        ->addToNewRow();


                echo $studentADForm->getFormHTML();
                ?>
            </div>
            <div class="tab-pane fade" id="nav-student-ga" role="tabpanel" aria-labelledby="nav-student-tab">
                <?php
                $studentGAForm = new Form('/teams/editStudents/' . $this->team[Schema::GRADE_ID[Schema::COLUMN]]);
                $studentGAForm->generateGAForm($this->teamID, $this->studentGASettings, Schema::TEAM, 'Student')
                        ->buildUpdateButton('Update GA')
                        ->addToNewRow();


                echo $studentGAForm->getFormHTML();
                ?>
            </div>
        </div>


    </div>

</div>


