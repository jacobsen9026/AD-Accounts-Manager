

<?php

use app\database\Schema;
use system\app\Form;
use app\models\district\ActiveDirectory;

echo $this->view('layouts/setup_navbar');
?>
<div class="p-5">
    <?php
    //var_dump($this->grade);
    ?>
    <h4 class="mb-5">Editing <?php echo $this->grade[Schema::GRADEDEFINITION_DISPLAY_NAME[Schema::COLUMN]]; ?> at <?= $this->school[Schema::SCHOOL_NAME[Schema::COLUMN]] ?></h4>




    <?php
//var_dump($this->district);
    $form = new Form('/grades/edit/' . $this->grade[Schema::GRADE_ID[Schema::COLUMN]]);
    $form->buildTextInput('Parent Email Group', Schema::GRADE_PARENT_EMAIL_GROUP[Schema::NAME], $this->grade[Schema::SCHOOL_PARENT_EMAIL_GROUP[Schema::COLUMN]])
            ->appendInput('@' . $this->district[Schema::DISTRICT_GA_FQDN[Schema::COLUMN]])
            ->addToRow(3)
            ->buildCustomButton('Add/Manage Teams', 'warning', '/teams/show/' . $this->grade[Schema::GRADE_ID[Schema::COLUMN]])
            ->addToRow(99);

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
                var_dump(ActiveDirectory::getField(Schema::GRADE, $this->gradeID, Schema::ACTIVEDIRECTORY_OU, 'Staff'));
                $staffADForm = new Form('/grades/editStaff/' . $this->grade[Schema::GRADE_ID[Schema::COLUMN]]);

                $staffADForm->buildTextInput('Staff Active Directory OU',
                                Schema::ACTIVEDIRECTORY_OU,
                                $this->staffADSettings[Schema::ACTIVEDIRECTORY_OU[Schema::COLUMN]],
                                '/schools/school_name/',
                                ActiveDirectory::getField(Schema::GRADE, $this->gradeID, Schema::ACTIVEDIRECTORY_OU, 'Staff'))
                        ->addToRow(1)
                        ->buildTextInput('Staff Active Directory User Description',
                                Schema::ACTIVEDIRECTORY_DESCRIPTION,
                                $this->staffADSettings[Schema::ACTIVEDIRECTORY_DESCRIPTION[Schema::COLUMN]])
                        ->addToRow(2)
                        ->buildTextInput('Staff Active Directory Group',
                                Schema::ACTIVEDIRECTORY_GROUP,
                                $this->staffADSettings[Schema::ACTIVEDIRECTORY_GROUP[Schema::COLUMN]])
                        ->addToRow()
                        ->buildTextInput('Staff Active Directory Home Path',
                                Schema::ACTIVEDIRECTORY_HOME_PATH,
                                $this->staffADSettings[Schema::ACTIVEDIRECTORY_HOME_PATH[Schema::COLUMN]])
                        ->appendInput(DIRECTORY_SEPARATOR . 'username')
                        ->addToRow(3)
                        ->buildTextInput('Staff Active Directory Logon Script',
                                Schema::ACTIVEDIRECTORY_LOGON_SCRIPT,
                                $this->staffADSettings[Schema::ACTIVEDIRECTORY_LOGON_SCRIPT[Schema::COLUMN]])
                        ->addToRow(4)
                        ->buildUpdateButton()->addToRow(100);

                echo $staffADForm->getFormHTML();
                ?>
            </div>
            <div class="tab-pane fade" id="nav-staff-ga" role="tabpanel" aria-labelledby="nav-student-tab">
                <?php
                $staffGAForm = new Form('/grades/editStaff/' . $this->grade[Schema::GRADE_ID[Schema::COLUMN]]);

                $staffGAForm->buildTextInput('Staff Google Apps OU',
                                Schema::GOOGLEAPPS_OU,
                                $this->staffGASettings[Schema::GOOGLEAPPS_OU[Schema::COLUMN]])
                        ->addToRow(1);

                $staffGAForm->buildTextInput('Staff Google Apps Group',
                                Schema::GOOGLEAPPS_GROUP,
                                $this->staffGASettings[Schema::GOOGLEAPPS_GROUP[Schema::COLUMN]])
                        ->addToRow();

                $staffGAForm->buildTextInput('Staff Google Apps Username Format',
                                Schema::GOOGLEAPPS_OTHER_GROUPS,
                                $this->staffGASettings[Schema::GOOGLEAPPS_OTHER_GROUPS[Schema::COLUMN]])
                        ->addToRow(2);
                $staffGAForm->buildTextInput('Staff Google Apps Username Format',
                                Schema::GOOGLEAPPS_USERNAME_FORMAT,
                                $this->staffGASettings[Schema::GOOGLEAPPS_USERNAME_FORMAT[Schema::COLUMN]])
                        ->addToRow()
                        ->buildUpdateButton()->addToRow(100);
                echo $staffGAForm->getFormHTML();
                ?>
            </div>
            <div class="tab-pane fade" id="nav-student-ad" role="tabpanel" aria-labelledby="nav-staff-tab">
                <?php
                $studentADForm = new Form('/grades/editStudents/' . $this->grade[Schema::GRADE_ID[Schema::COLUMN]]);

                $studentADForm->buildTextInput('Student Active Directory OU',
                                Schema::ACTIVEDIRECTORY_OU,
                                $this->studentADSettings[Schema::ACTIVEDIRECTORY_OU[Schema::COLUMN]])
                        ->addToRow(1)
                        ->buildTextInput('Student Active Directory User Description',
                                Schema::ACTIVEDIRECTORY_DESCRIPTION,
                                $this->studentADSettings[Schema::ACTIVEDIRECTORY_DESCRIPTION[Schema::COLUMN]])
                        ->addToRow(2)
                        ->buildTextInput('Student Active Directory Group',
                                Schema::ACTIVEDIRECTORY_GROUP,
                                $this->studentADSettings[Schema::ACTIVEDIRECTORY_GROUP[Schema::COLUMN]])
                        ->addToRow()
                        ->buildTextInput('Student Active Directory Home Path',
                                Schema::ACTIVEDIRECTORY_HOME_PATH,
                                $this->studentADSettings[Schema::ACTIVEDIRECTORY_HOME_PATH[Schema::COLUMN]])
                        ->appendInput(DIRECTORY_SEPARATOR . 'username')
                        ->addToRow(3)
                        ->buildTextInput('Student Active Directory Logon Script',
                                Schema::ACTIVEDIRECTORY_LOGON_SCRIPT,
                                $this->studentADSettings[Schema::ACTIVEDIRECTORY_LOGON_SCRIPT[Schema::COLUMN]])
                        ->addToRow(4)
                        ->buildUpdateButton()->addToRow(100);

                echo $studentADForm->getFormHTML();
                ?>
            </div>
            <div class="tab-pane fade" id="nav-student-ga" role="tabpanel" aria-labelledby="nav-student-tab">
                <?php
                $studentGAForm = new Form('/grades/editStudents/' . $this->grade[Schema::GRADE_ID[Schema::COLUMN]]);

                $studentGAForm->buildTextInput('Student Google Apps OU',
                                Schema::GOOGLEAPPS_OU,
                                $this->studentGASettings[Schema::GOOGLEAPPS_OU[Schema::COLUMN]])
                        ->addToRow(1);

                $studentGAForm->buildTextInput('Student Google Apps Group',
                                Schema::GOOGLEAPPS_GROUP,
                                $this->studentGASettings[Schema::GOOGLEAPPS_GROUP[Schema::COLUMN]])
                        ->addToRow();

                $studentGAForm->buildTextInput('Student Google Apps Username Format',
                                Schema::GOOGLEAPPS_OTHER_GROUPS,
                                $this->studentGASettings[Schema::GOOGLEAPPS_OTHER_GROUPS[Schema::COLUMN]])
                        ->addToRow(2);
                $studentGAForm->buildTextInput('Student Google Apps Username Format',
                                Schema::GOOGLEAPPS_USERNAME_FORMAT,
                                $this->studentGASettings[Schema::GOOGLEAPPS_USERNAME_FORMAT[Schema::COLUMN]])
                        ->addToRow()
                        ->buildUpdateButton()->addToRow(100);
                echo $studentGAForm->getFormHTML();
                ?>
            </div>
        </div>


    </div>

</div>


















