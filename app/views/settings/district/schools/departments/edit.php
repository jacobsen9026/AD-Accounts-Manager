

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
    <h4>Editing Department <?php echo $this->department[Schema::DEPARTMENT_NAME[Schema::COLUMN]]; ?> at <?= $this->school[Schema::SCHOOL_NAME[Schema::COLUMN]] ?></h4>

    <?php
//var_dump($this->district);
    $form = new Form('/settings/departments/edit/' . $this->department[Schema::SCHOOL_ID[Schema::COLUMN]]);
    $form->buildTextInput('Name', Schema::DEPARTMENT_NAME[Schema::NAME], $this->departmentName)
            ->addToRow(1);

    $form->buildUpdateButton()->addToRow(100);

    echo $form->getFormHTML();
    ?>

    <div class="ad_ga_settings" id="ad_ga_settings">

        <nav class=" nav-fill nav-justified">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="rounded-0 shadow bg-primary text-light nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-staff-ad" role="tab" aria-controls="nav-home" aria-selected="true">Staff Active Directory</a>
                <?php
                if (!$this->district[Schema::DISTRICT_USING_GADS[Schema::COLUMN]]) {
                    ?>
                    <a class="rounded-0 shadow bg-primary text-light nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-staff-ga" role="tab" aria-controls="nav-profile" aria-selected="false">Staff Google Apps</a>
                    <?php
                }
                ?>
            </div>
        </nav>
        <div class="shadow bg-light tab-content py-3 px-md-3" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-staff-ad" role="tabpanel" aria-labelledby="nav-staff-tab">
                <?php
                //var_dump(ActiveDirectory::getField(Schema::SCHOOL, $this->departmentID, Schema::ACTIVEDIRECTORY_OU, 'Staff'));
                $staffADForm = new Form('/settings/departments/editStaff/' . $this->department[Schema::SCHOOL_ID[Schema::COLUMN]]);
                $staffADForm->generateADForm($this->departmentID, $this->staffADSettings, Schema::DEPARTMENT)
                        ->buildUpdateButton()->addToRow(100);
                echo $staffADForm->getFormHTML();
                ?>
            </div>
            <?php
            if (!$this->district[Schema::DISTRICT_USING_GADS[Schema::COLUMN]]) {
                ?>
                <div class="tab-pane fade" id="nav-staff-ga" role="tabpanel" aria-labelledby="nav-student-tab">
                    <?php
                    $staffGAForm = new Form('/settings/departments/editStaff/' . $this->department[Schema::SCHOOL_ID[Schema::COLUMN]]);
                    $staffGAForm->generateGAForm($this->departmentID, $this->staffGASettings, Schema::DEPARTMENT)
                            ->buildUpdateButton('Update GA')
                            ->addToNewRow();

                    echo $staffGAForm->getFormHTML();
                    ?>
                </div>
                <?php
            }
            ?>
        </div>


    </div>

</div>


