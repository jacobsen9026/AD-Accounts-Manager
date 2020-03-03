

<?php

use app\database\Schema;
use system\app\forms\Form;
use app\models\district\ActiveDirectory;

echo $this->view('layouts/setup_navbar');
?>
<div class="p-md-5">
    <?php
    //var_dump($this->grade);
    ?>
    <h4 class="mb-5">Editing <?php echo $this->grade[Schema::GRADEDEFINITION_DISPLAY_NAME[Schema::COLUMN]]; ?> at <?= $this->school[Schema::SCHOOL_NAME[Schema::COLUMN]] ?></h4>




    <?php
//var_dump($this->district);
    $form = new Form('/settings/grades/edit/' . $this->grade[Schema::GRADE_ID[Schema::COLUMN]]);
    $form->buildTextInput('Parent Email Group', Schema::GRADE_PARENT_EMAIL_GROUP[Schema::NAME], $this->grade[Schema::SCHOOL_PARENT_EMAIL_GROUP[Schema::COLUMN]])
            ->appendInput('@' . $this->district[Schema::DISTRICT_GA_FQDN[Schema::COLUMN]])
            ->addToRow(3)
            ->buildCustomButton('Add/Manage Teams', 'warning', '/settings/teams/show/' . $this->grade[Schema::GRADE_ID[Schema::COLUMN]])
            ->addToRow(99);

    $form->buildUpdateButton()->addToRow(100);

    echo $form->getFormHTML();
    ?>
    <button class="my-1 btn btn-success collapse show ad_ga_settings"  type="button" data-toggle="collapse" data-target=".ad_ga_settings" aria-expanded="false" aria-controls="collapseExample">
        Show More Settings
    </button>
    <div class="collapse ad_ga_settings" id="ad_ga_settings">

        <nav class="district-settings-nav sticky-top nav-fill nav-justified">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="rounded-0 shadow bg-primary text-light nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-staff-ad" role="tab" aria-controls="nav-home" aria-selected="true">Staff Active Directory</a>
                <?php
                if (!$this->district[Schema::DISTRICT_USING_GADS[Schema::COLUMN]]) {
                    ?>
                    <a class="rounded-0 shadow bg-primary text-light nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-staff-ga" role="tab" aria-controls="nav-profile" aria-selected="false">Staff Google Apps</a>
                    <?php
                }
                ?>
                <a class="rounded-0 shadow bg-primary text-light nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-student-ad" role="tab" aria-controls="nav-home" aria-selected="true">Student Active Directory</a>
                <?php
                if (!$this->district[Schema::DISTRICT_USING_GADS[Schema::COLUMN]]) {
                    ?>
                    <a class="rounded-0 shadow bg-primary text-light nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-student-ga" role="tab" aria-controls="nav-profile" aria-selected="false">Student Google Apps</a>
                    <?php
                }
                ?>
            </div>
        </nav>
        <div class="shadow bg-light tab-content py-3 px-md-3" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-staff-ad" role="tabpanel" aria-labelledby="nav-staff-tab">
                <?php
                //var_dump(ActiveDirectory::getField(Schema::GRADE, $this->gradeID, Schema::ACTIVEDIRECTORY_OU, 'Staff'));
                $staffADForm = new Form('/settings/grades/editStaff/' . $this->grade[Schema::GRADE_ID[Schema::COLUMN]]);
                $staffADForm->generateADForm($this->gradeID, $this->staffADSettings, Schema::GRADE, 'Staff')
                        ->buildUpdateButton('Update AD')
                        ->addToNewRow();


                echo $staffADForm->getFormHTML();
                ?>
            </div>
            <?php
            if (!$this->district[Schema::DISTRICT_USING_GADS[Schema::COLUMN]]) {
                ?>
                <div class="tab-pane fade" id="nav-staff-ga" role="tabpanel" aria-labelledby="nav-student-tab">
                    <?php
                    $staffGAForm = new Form('/settings/grades/editStaff/' . $this->grade[Schema::GRADE_ID[Schema::COLUMN]]);
                    $staffGAForm->generateGAForm($this->gradeID, $this->staffGASettings, Schema::GRADE)
                            ->buildUpdateButton('Update GA')
                            ->addToNewRow();

                    echo $staffGAForm->getFormHTML();
                    ?>
                </div>
                <?php
            }
            ?>
            <div class="tab-pane fade" id="nav-student-ad" role="tabpanel" aria-labelledby="nav-staff-tab">
                <?php
                $studentADForm = new Form('/settings/grades/editStudents/' . $this->grade[Schema::GRADE_ID[Schema::COLUMN]]);
                $studentADForm->generateADForm($this->gradeID, $this->studentADSettings, Schema::GRADE, 'Student')
                        ->buildUpdateButton('Update AD')
                        ->addToNewRow();



                echo $studentADForm->getFormHTML();
                ?>
            </div>
            <?php
            if (!$this->district[Schema::DISTRICT_USING_GADS[Schema::COLUMN]]) {
                ?>
                <div class="tab-pane fade" id="nav-student-ga" role="tabpanel" aria-labelledby="nav-student-tab">
                    <?php
                    $studentGAForm = new Form('/settings/grades/editStudents/' . $this->grade[Schema::GRADE_ID[Schema::COLUMN]]);
                    $studentGAForm->generateGAForm($this->gradeID, $this->studentGASettings, Schema::GRADE, 'Student')
                            ->buildUpdateButton('Update GA')
                            ->addToNewRow();



                    echo $studentGAForm->getFormHTML();
                    ?>
                </div>
                <?php
            }
            ?>
        </div>


    </div>

</div>


















