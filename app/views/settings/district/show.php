<?php
/* @var $district District */

use app\database\Schema;
use system\app\forms\Form;
use app\models\district\DistrictDatabase;
use app\api\GAM;
use app\models\district\District;

echo $this->modal('deleteDistrict');
//var_dump($this->staffADSettings);
//var_dump($this->staffADSettings[Schema::ACTIVEDIRECTORY_OU[Schema::COLUMN]]);


/* @var $district District  */
/** @var District $district */
$district = $this->district;
$ad = new app\api\AD($district->getID());
$adTestResult = $ad->getConnectionResult();
$defaultBaseDN = DistrictDatabase::parseBaseDNFromFQDN(DistrictDatabase::getAD_FQDN($this->districtID));
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
            echo $district->getName();
//var_dump($this->district);
            ?>
        </strong>
        <button type="button" class="close text-danger" aria-label="Close" data-toggle="modal" data-target="#deleteDistrictModal">
            <span aria-hidden="true">&times;</span>
        </button>

    </div>



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
                    $district->getName())
            ->addToRow(1)
            ->buildTextInput('Abbreviation',
                    Schema::DISTRICT_ABBREVIATION,
                    $district->getAbbr())
            ->medium()
            ->addToNewRow()
            ->buildTextInput('Active Directory Domain NetBIOS',
                    Schema::DISTRICT_AD_NETBIOS,
                    $district->getAdNetBIOS())
            ->addToRow()
            ->buildTextInput('Active Directory FQDN',
                    Schema::DISTRICT_AD_FQDN,
                    $district->getAdFQDN())
            ->addToNewRow()
            ->buildTextInput('Active Directory Base DN',
                    Schema::DISTRICT_AD_BASEDN,
                    $district->getAdBaseDN(),
                    'The point from which the application searches from.',
                    $defaultBaseDN
            )
            ->addToRow()
            ->buildTextInput('Active Directory Username',
                    Schema::DISTRICT_AD_USERNAME,
                    $district->getAdUsername(),
                    'Enter an account with admin privileges to the district OU\'s')
            ->addToNewRow()
            ->buildPasswordInput('Active Directory Password',
                    Schema::DISTRICT_AD_PASSWORD,
                    $district->getAdPassword(),
                    'Enter password for admin user')
            ->addToRow()
            ->buildTextInput('Active Directory Student Group',
                    Schema::DISTRICT_AD_STUDENT_GROUP,
                    $district->getAdStudentGroupName(),
                    'This group should contain all active and inactive students')
            ->addToNewRow()
            ->buildStatusCheck('LDAP Connection Test',
                    $adTestResult)
            ->addToRow();
    if ($adTestResult == "true") {
        $form->buildAJAXStatusCheck('LDAP Permission Test',
                        '/api/ldap/testPerms',
                        [['districtID', $district->getId()]],
                        $district->getAdBaseDN())
                ->addToRow();
    }

    $form->buildTextInput('Google Apps FQDN',
                    Schema::DISTRICT_GA_FQDN,
                    $district->getGsFQDN())
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
                    $district->getParentEmailGroup(),
                    'Do not enter the domain name.')
            ->appendInput('@' . $district->getGsFQDN())
            ->addToNewRow()

            //->addToForm($tabs, 20)
            ->insertObjectIDInput(Schema::DISTRICT_ID, $this->districtID)
            ->buildCustomButton('Schools/Buildings',
                    'warning',
                    '/settings/schools/show/' . $district->getId())
            ->addToRow(99)
            ->buildUpdateButton()
            ->addToRow(100);
    echo $form->getFormHTML();

    $form = new Form('/settings/district/edit/' . $district->getId(), 'editDistrict');
    $name = new system\app\forms\FormText("Name", "", "name", $district->getName());
    $name->full();
    $abbr = new system\app\forms\FormText("Abbreviation", "", "abbr", $district->getAbbr());
    $netBIOS = new system\app\forms\FormText("Active Directory Domain NetBIOS", "", "adNetBIOS", $district->getAdNetBIOS());
    $adFQDN = new system\app\forms\FormText("Active Directory FQDN", "", "adFQDN", $district->getAdFQDN());
    $adBaseDN = new system\app\forms\FormText("Active Directory Base DN", "The point from which the application searches from.", "adBaseDN", $district->getAdBaseDN());
    $adUsername = new system\app\forms\FormText("Active Directory Username", "Enter an account with admin privileges to the district OU's", "adUsername", $district->getAdUsername());
    $adPassword = new system\app\forms\FormText("Active Directory Password", "Enter password for admin user", "adPassword", $district->getAdPassword());
    $adPassword->password();
    $adStudentGroup = new system\app\forms\FormText("Active Directory Student Group", "This group should contain all active and inactive students as well as all student groups", "adStudentGroup", $district->getAdStudentGroupName());
    $adConnectionCheck = new system\app\forms\FormHTML();

    $valueDisplay = '';
    if ($adTestResult == '1') {

        $classes = 'fas fa-check-circle text-success';
    }
    $adTestResult = '<h1><i class="' . $classes . '"  data-toggle="tooltip" data-placement="top"></i></h1>' . $valueDisplay;


    $adConnectionCheck->setLabel("AD Connection Test")
            ->setHtml($adTestResult);



    $adPermissionTestButton = new \system\app\forms\FormButton("AD Permission Test", $district->getAdBaseDN());

    $form->addElementToNewRow($name)
            ->addElementToNewRow($abbr)
            ->addElementToCurrentRow($netBIOS)
            ->addElementToNewRow($adFQDN)
            ->addElementToCurrentRow($adBaseDN)
            ->addElementToNewRow($adUsername)
            ->addElementToCurrentRow($adPassword)
            ->addElementToNewRow($adStudentGroup)
            ->addElementToCurrentRow($adConnectionCheck)
            ->addElementToCurrentRow($adPermissionTestButton);
    echo $form->print();
    ?>





</div>
