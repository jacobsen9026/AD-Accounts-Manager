

<?php

use app\database\Schema;
use system\app\Form;

echo $this->view('layouts/setup_navbar');
$this->schoolName = $this->school[Schema::SCHOOL_NAME[Schema::COLUMN]];
?>
<div class="p-5">
    <?php
    //var_dump($this->school);
    ?>
    <head>
        <title>Editing <?php echo $this->schoolName; ?></title>
    </head>
    <h4>Editing <?php echo $this->schoolName; ?></h4>

    <?php
    ob_start();
    ?>
    <button class="my-1 btn btn-success collapse show ad_ga_settings"  type="button" data-toggle="collapse" data-target=".ad_ga_settings" aria-expanded="false" aria-controls="collapseExample">
        Show More Settings
    </button>
    <div class="collapse ad_ga_settings" id="ad_ga_settings">
        <div class="container-fluid px-0 px-md-5">
            <nav class="rounded-top nav-fill nav-justified">
                <div class = "nav nav-tabs" id = "nav-tab" role = "tablist">
                    <a class = "shadow bg-primary text-light nav-item nav-link active" id = "nav-ad-tab" data-toggle = "tab" href = "#nav-ad" role = "tab" aria-controls = "nav-home" aria-selected = "true">Active Directory</a>
                    <?php
                    if (!$this->district[Schema::DISTRICT_USING_GADS[Schema::COLUMN]]) {
                        ?>
                        <a class = "shadow bg-primary text-light nav-item nav-link" id = "nav-ga-tab" data-toggle = "tab" href = "#nav-ga" role = "tab" aria-controls = "nav-profile" aria-selected = "false">Google Apps</a>
                        <?php
                    }
                    ?>
                </div>
            </nav>
            <div class = "shadow bg-light tab-content py-3 px-md-3" id = "nav-tabContent">
                <div class = "tab-pane fade show active" id = "nav-ad" role = "tabpanel" aria-labelledby = "nav-ad-tab">
                    <?php
                    $adForm = new Form('/settings/schools/edit/' . $this->school[Schema::SCHOOL_ID[Schema::COLUMN]]);
                    $adForm->subForm()
                            ->generateADForm($this->schoolID, $this->staffADSettings, Schema::SCHOOL);
                    echo $adForm->getFormHTML();
                    ?>
                </div>
                <?php
                if (!$this->district[Schema::DISTRICT_USING_GADS[Schema::COLUMN]]) {
                    ?>

                    <div class="tab-pane fade pt-3" id="nav-ga" role="tabpanel" aria-labelledby="nav-ga-tab">
                        <?php
                        $gaForm = new Form('/settings/schools/edit/' . $this->school[Schema::SCHOOL_ID[Schema::COLUMN]]);
                        $gaForm->subForm()
                                ->generateGAForm($this->schoolID, $this->staffGASettings, Schema::SCHOOL);

                        echo $gaForm->getFormHTML();
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
    $tabs = ob_get_clean();



    //var_dump($this->district);
    $form = new Form('/settings/schools/edit/' . $this->school[Schema::SCHOOL_ID[Schema::COLUMN]]);
    $form->buildTextInput('Name', Schema::SCHOOL_NAME[Schema::NAME], $this->schoolName)
            ->addToRow(1)
            ->buildTextInput('Abbreviation',
                    Schema::SCHOOL_ABBREVIATION,
                    $this->school[Schema::SCHOOL_ABBREVIATION[Schema::COLUMN]])
            ->medium()
            ->addToRow(2)
            ->buildTextInput('Parent Email Group', Schema::SCHOOL_PARENT_EMAIL_GROUP[Schema::NAME], $this->school[Schema::SCHOOL_PARENT_EMAIL_GROUP[Schema::COLUMN]])
            ->appendInput('@' . $this->district[Schema::DISTRICT_GA_FQDN[Schema::COLUMN]])
            ->addToRow(3)
            ->addToForm($tabs, 6)
            ->buildCustomButton('Edit Departments', 'warning', '/settings/departments/show/' . $this->school[Schema::SCHOOL_ID[Schema::COLUMN]])
            ->addToRow(99)
            ->buildCustomButton('Edit Grades', 'warning', '/settings/grades/show/' . $this->school[Schema::SCHOOL_ID[Schema::COLUMN]])
            ->addToRow(99);

    $form->buildUpdateButton('Update ' . $this->schoolName)->addToRow(100);

    echo $form->getFormHTML();
    ?>

</div>


