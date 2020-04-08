<?php
/* @var $district District */

use app\database\Schema;
use system\app\forms\Form;
use app\models\district\District;
use app\api\GAM;

echo $this->modal('deleteDistrict');
//var_dump($this->staffADSettings);
//var_dump($this->staffADSettings[Schema::ACTIVEDIRECTORY_OU[Schema::COLUMN]]);
/** @var $ad AD */
$ad = new app\api\AD($this->districtID);
$adTestResult = $ad->getConnectionResult();
$defaultBaseDN = District::parseBaseDNFromFQDN(District::getAD_FQDN($this->districtID));
$clientSecretExists = GAM::get()->clientSecretExists();
$google = GAM::get();
//var_dump($adTestResult);
?>
<?= $this->view('layouts/setup_navbar'); ?>

<div  id="settings-content" class = "container-fluid px-md-5 pt-3">

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
                    $adForm = new Form('/settings/districts/edit/' . $this->districtID);
                    $adForm->subForm();
                    $adForm->buildTextInput('Staff Active Directory OU',
                                    Schema::ACTIVEDIRECTORY_OU,
                                    $this->district[Schema::DISTRICT_AD_BASEDN[Schema::COLUMN]])
                            ->disable()
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
                            ->addToRow(4);


                    echo $adForm->getFormHTML();
                    ?>
                </div>
                <?php
                if (!$this->district[Schema::DISTRICT_USING_GADS[Schema::COLUMN]]) {
                    ?>

                    <div class="tab-pane fade pt-3" id="nav-ga" role="tabpanel" aria-labelledby="nav-ga-tab">
                        <?php
                        $gaForm = new Form('/settings/districts/edit/' . $this->districtID);
                        $gaForm->subForm();
                        $gaForm->buildTextInput('Staff Google Apps OU',
                                        Schema::GOOGLEAPPS_OU,
                                        $this->staffGASettings[Schema::GOOGLEAPPS_OU[Schema::COLUMN]])
                                ->addToRow(1);

                        $gaForm->buildTextInput('Staff Google Apps Group',
                                        Schema::GOOGLEAPPS_GROUP,
                                        $this->staffGASettings[Schema::GOOGLEAPPS_GROUP[Schema::COLUMN]])
                                ->addToRow();

                        $gaForm->buildTextInput('Staff Google Apps Other Groups',
                                        Schema::GOOGLEAPPS_OTHER_GROUPS,
                                        $this->staffGASettings[Schema::GOOGLEAPPS_OTHER_GROUPS[Schema::COLUMN]])
                                ->addToRow(2);

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
    ?>

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <?php
//var_dump(\app\models\district\GoogleApps::getField(Schema::GRADE, $this->districtID, Schema::GOOGLEAPPS_OU, 'Staff'));
    $form = new Form('/settings/districts/edit/' . $this->districtID);
    $form->buildTextInput('Name',
                    Schema::DISTRICT_NAME,
                    $this->district[Schema::DISTRICT_NAME[Schema::COLUMN]])->addToRow(1)
            ->buildTextInput('Abbreviation',
                    Schema::DISTRICT_ABBREVIATION,
                    $this->district[Schema::DISTRICT_ABBREVIATION[Schema::COLUMN]])
            ->medium()
            ->addToNewRow()
            ->buildTextInput('Active Directory Domain NetBIOS',
                    Schema::DISTRICT_AD_NETBIOS,
                    $this->district[Schema::DISTRICT_AD_NETBIOS[Schema::COLUMN]])
            ->addToRow()
            ->buildTextInput('Active Directory FQDN',
                    Schema::DISTRICT_AD_FQDN,
                    $this->district[Schema::DISTRICT_AD_FQDN[Schema::COLUMN]])
            ->addToNewRow()
            ->buildTextInput('Active Directory Base DN',
                    Schema::DISTRICT_AD_BASEDN,
                    $this->district[Schema::DISTRICT_AD_BASEDN[Schema::COLUMN]],
                    'The point from which the application searches from.',
                    $defaultBaseDN
            )
            ->addToRow()
            ->buildTextInput('Active Directory Username',
                    Schema::DISTRICT_AD_USERNAME,
                    $this->district[Schema::DISTRICT_AD_USERNAME[Schema::COLUMN]],
                    'Enter an account with admin privileges to the district OU\'s')
            ->addToNewRow()
            ->buildPasswordInput('Active Directory Password',
                    Schema::DISTRICT_AD_PASSWORD,
                    $this->district[Schema::DISTRICT_AD_PASSWORD[Schema::COLUMN]],
                    'Enter password for admin user')
            ->addToRow()
            ->buildTextInput('Active Directory Student Group',
                    Schema::DISTRICT_AD_STUDENT_GROUP,
                    $this->district[Schema::DISTRICT_AD_STUDENT_GROUP[Schema::COLUMN]],
                    'This group should contain all active and inactive students')
            ->addToNewRow()
            ->buildStatusCheck('LDAP Connection Test',
                    $adTestResult)
            ->addToRow();
    if ($adTestResult == "true") {
        $form->buildAJAXStatusCheck('LDAP Permission Test',
                        '/api/ldap/testPerms',
                        [['districtID', $this->districtID]],
                        $this->district[Schema::DISTRICT_AD_BASEDN[Schema::COLUMN]])
                ->addToRow();
    }

    $form->buildTextInput('Google Apps FQDN',
                    Schema::DISTRICT_GA_FQDN,
                    $this->district[Schema::DISTRICT_GA_FQDN[Schema::COLUMN]])
            ->addToNewRow();
            

   
 
        if (!$clientSecretExists) {


            $jsonInstructions = 'If you do not have a json you will need to make/download it from the '
                    . '<a href="https://console.developers.google.com/apis/dashboard">Google Developers Console</a>'
                    . '<br/>You can create a new project or add an Oauth credential to an existing one.';

            $form->buildFileInput("client_secret",
                            'Upload a client_secret.json',
                            'Choose your client_secret.json',
                            'application/JSON')
                    ->addToNewRow()
                    ->addToForm($jsonInstructions);
            //->buildCustomButton('Check OAuth Validity', 'primary', 'api/gam/test')
            //->addOnClickListenerFunction($onClick)
            // ->addToNewRow()
            //->addToNewRow();
        } else {

            $form->buildFileInput("client_secret",
                            'Replace client_secret.json',
                            'Choose your client_secret.json',
                            'application/JSON')
                    ->addToNewRow();
        }


        if (!$google->isAuthorized() and $google->clientSecretExists()) {
            $form->buildCustomButton("Authorize this app for Google!", "primary", $google->getAuthUrl())
                    ->addToRow();
        }

        if ($google->isAuthorized()) {
            $scopes = '';
            foreach ($google->getScopes() as $scope) {
                $scopes .= $scope . "\n";
            }
            $scopes = "   Scopes \n " . $scopes;
            $form->buildStatusCheck("Google Connected", true, $google->getDomainNames()[0], $scopes)
                    ->addToRow()
                    ->buildCustomButton("Re-Authorize / Update Permissions", "primary", $google->getAuthUrl())
                    ->addToRow();
        }
    

    $form->buildTextInput('District Parent Email Group',
                    Schema::DISTRICT_PARENT_EMAIL_GROUP,
                    $this->district[Schema::DISTRICT_PARENT_EMAIL_GROUP[Schema::COLUMN]],
                    'Do not enter the domain name.')
            ->appendInput('@' . $this->district[Schema::DISTRICT_GA_FQDN[Schema::COLUMN]])
            ->addToNewRow()
            ->buildDropDownInput('Staff Username Format',
                    Schema::DISTRICT_STAFF_USERNAME_FORMAT,
                    app\models\district\UsernameFormat::getUsernameFormats())
            ->center()
            ->addToNewRow()
            ->buildDropDownInput('Student Username Format',
                    Schema::DISTRICT_STUDENT_USERNAME_FORMAT,
                    app\models\district\UsernameFormat::getUsernameFormats())
            ->center()
            ->addToRow()
            ->addToForm($tabs, 20)
            ->insertObjectIDInput(Schema::DISTRICT_ID, $this->districtID)
            ->buildCustomButton('Schools/Buildings',
                    'warning',
                    '/settings/schools/show/' . $this->district[app\database\Schema::DISTRICT_ID[app\database\Schema::COLUMN]])
            ->addToRow(99)
            ->buildUpdateButton()
            ->addToRow(100);
    echo $form->getFormHTML();
    ?>





</div>
