<?php
/* @var $district District */

use app\database\Schema;
use system\app\Form;

echo $this->modal('deleteDistrict');
//var_dump($this->staffADSettings);
//var_dump($this->staffADSettings[Schema::ACTIVEDIRECTORY_OU[Schema::COLUMN]]);
?>
<?= $this->view('layouts/setup_navbar'); ?>
<div class = "container-fluid px-md-5">

    <div class = "mb-3">
        <strong>Editing
            <?php
            //var_dump(Schema::DISTRICT_NAME);
            echo $this->district[Schema::DISTRICT_NAME[Schema::COLUMN]];
            //var_dump($this->district);
            ?>
        </strong>
        <button type="button" class="close text-danger" aria-label="Close" data-toggle="modal" data-target="#deleteDistrictModal">
            <span aria-hidden="true">&times;</span>
        </button>

    </div>

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
                    <a class = "shadow bg-primary text-light nav-item nav-link" id = "nav-ga-tab" data-toggle = "tab" href = "#nav-ga" role = "tab" aria-controls = "nav-profile" aria-selected = "false">Google Apps</a>
                </div>
            </nav>
            <div class = "shadow bg-light tab-content py-3 px-md-3" id = "nav-tabContent">
                <div class = "tab-pane fade show active" id = "nav-ad" role = "tabpanel" aria-labelledby = "nav-ad-tab">
                    <?php
                    $adForm = new Form('/districts/edit/' . $this->districtID);
                    $adForm->subForm();
                    $adForm->buildTextInput('Staff Active Directory OU',
                                    Schema::ACTIVEDIRECTORY_OU,
                                    $this->staffADSettings[Schema::ACTIVEDIRECTORY_OU[Schema::COLUMN]])
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
                            ->buildDropDownInput('Staff Active Directory Username Format',
                                    Schema::ACTIVEDIRECTORY_USERNAME_FORMAT,
                                    app\models\district\UsernameFormat::getUsernameFormats())
                            ->center()
                            ->addToRow(5);
                    echo $adForm->getFormHTML();
                    ?>
                </div>
                <div class="tab-pane fade pt-3" id="nav-ga" role="tabpanel" aria-labelledby="nav-ga-tab">
                    <?php
                    $gaForm = new Form('/districts/edit/' . $this->districtID);
                    $gaForm->subForm();
                    $gaForm->buildTextInput('Staff Google Apps OU',
                                    Schema::GOOGLEAPPS_OU,
                                    $this->staffGASettings[Schema::GOOGLEAPPS_OU[Schema::COLUMN]])
                            ->addToRow(1);

                    $gaForm->buildTextInput('Staff Google Apps Group',
                                    Schema::GOOGLEAPPS_GROUP,
                                    $this->staffGASettings[Schema::GOOGLEAPPS_GROUP[Schema::COLUMN]])
                            ->addToRow();

                    $gaForm->buildTextInput('Staff Google Apps Username Format',
                                    Schema::GOOGLEAPPS_OTHER_GROUPS,
                                    $this->staffGASettings[Schema::GOOGLEAPPS_OTHER_GROUPS[Schema::COLUMN]])
                            ->addToRow(2);
                    $gaForm->buildTextInput('Staff Google Apps Username Format',
                                    Schema::GOOGLEAPPS_USERNAME_FORMAT,
                                    $this->staffGASettings[Schema::GOOGLEAPPS_USERNAME_FORMAT[Schema::COLUMN]])
                            ->addToRow();
                    echo $gaForm->getFormHTML();
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    $tabs = ob_get_clean();









    //var_dump(\app\models\district\GoogleApps::getField(Schema::GRADE, $this->districtID, Schema::GOOGLEAPPS_OU, 'Staff'));
    $form = new Form('/districts/edit/' . $this->districtID);
    $form->buildTextInput('Name',
            Schema::DISTRICT_NAME,
            $this->district[Schema::DISTRICT_NAME[Schema::COLUMN]])->addToRow(1);
    $form->buildTextInput('Abbreviation',
                    Schema::DISTRICT_ABBREVIATION,
                    $this->district[Schema::DISTRICT_ABBREVIATION[Schema::COLUMN]])
            ->medium()
            ->addToRow(2);

    $form->buildTextInput('Active Directory Domain NetBIOS',
                    Schema::DISTRICT_AD_NETBIOS,
                    $this->district[Schema::DISTRICT_AD_NETBIOS[Schema::COLUMN]])
            ->addToRow();
    $form->buildTextInput('Active Directory FQDN',
                    Schema::DISTRICT_AD_FQDN,
                    $this->district[Schema::DISTRICT_AD_FQDN[Schema::COLUMN]])
            ->addToRow(3);
    $form->buildTextInput('District Parent Email Group',
                    Schema::DISTRICT_PARENT_EMAIL_GROUP,
                    $this->district[Schema::DISTRICT_PARENT_EMAIL_GROUP[Schema::COLUMN]],
                    'Do not enter the domain name.')
            ->appendInput('@' . $this->district[Schema::DISTRICT_GA_FQDN[Schema::COLUMN]])
            ->addToRow(4);
    $form->buildTextInput('Google Apps FQDN',
                    Schema::DISTRICT_GA_FQDN,
                    $this->district[Schema::DISTRICT_GA_FQDN[Schema::COLUMN]])
            ->addToRow(3);

    $form->addToForm($tabs, 6);
    $form->insertObjectIDInput(Schema::DISTRICT_ID, $this->districtID);
    $form->buildCustomButton('Schools/Buildings',
                    'warning',
                    '/schools/show/' . $this->district[app\database\Schema::DISTRICT_ID[app\database\Schema::COLUMN]])
            ->addToRow(99);

    $form->buildUpdateButton()->addToRow(100);
    echo $form->getFormHTML();
    ?>





</div>
