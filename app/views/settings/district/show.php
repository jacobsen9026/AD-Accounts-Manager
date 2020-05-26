<?php
/* @var $district District */

use App\Models\Database\DistrictDatabase;
use System\App\Forms\Form;
use App\Api\GAM;
use System\App\Forms\FormFloatingButton;
use System\Encryption;
use App\Models\District\District;
use System\App\Forms\FormButton;
use System\App\Forms\FormText;
use System\App\Forms\FormHTML;


/* @var $district District */
/** @var District $district */
$district = $this->district;
$ad = new App\Api\AD($district->getID());
$adTestResult = $ad->getConnectionResult();

//$clientSecretExists = GAM::get()->clientSecretExists();
//$google = GAM::get();
//var_dump($adTestResult);
?>

<div id="districtOutput" class="container-fluid bg-white shadow p-3 mt-0 pb-5 mb-5">

    <div class="mb-3">
        <strong>Editing
            <?= $district->getName() ?>
        </strong>


    </div>


    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <?php
    $form = new Form('/settings/district/edit/' . $district->getId(), 'editDistrict');

    /**
     * @deprecated
     */
    $updateButtton = new FormButton("Update District");
    $updateButtton->medium()
        ->addAJAXRequest('/api/settings/district', 'districtOutput', $form);


    $updateButttonFloating = new FormFloatingButton('<i class="h3 mb-0 fas fa-check"></i>');
    $updateButttonFloating->setId('floatingSaveButton')
        ->addAJAXRequest('/api/settings/district', 'districtOutput', $form);

    $name = new FormText("Name", "", "name", $district->getName());
    $name->full();
    $action = new FormText('', '', 'aciton', 'updateDistrict');
    $action->hidden();
    $abbr = new FormText("Abbreviation", "", "abbr", $district->getAbbr());
    $netBIOS = new FormText("Active Directory Domain NetBIOS", "", "adNetBIOS", $district->getAdNetBIOS());
    $adFQDN = new FormText("Active Directory FQDN", "", "adFQDN", $district->getAdFQDN());
    $adBaseDN = new FormText("Active Directory Base DN", "The point from which the application searches from.", "adBaseDN", $district->getAdBaseDN());
    $adUsername = new FormText("Active Directory Username", "Enter an account with admin privileges to the district OU's", "adUsername", $district->getAdUsername());
    $adPassword = new FormText("Active Directory Password", "Enter password for admin user", "adPassword", Encryption::encrypt($district->getAdPassword()));
    $adPassword->isPassword();
    $adStudentGroup = new FormText("Active Directory Student Group", "This group should contain all active and inactive students as well as all student groups", "adStudentGroup", $district->getAdStudentGroupName());
    $adStaffGroup = new FormText("Active Directory Staff Group", "This group should contain all active staff as well as all staff groups", "adStaffGroup", $district->getAdStaffGroupName());

    $adConnectionCheck = new FormHTML();
    $useTLS = DistrictDatabase::getAD_UseTLS();
    $adUseTLS = new \System\App\Forms\FormSlider('Use TLS', 'Requires web server configuration', 'useTLS', $useTLS);

    $adUseTLS->addOption('No', 0, !$useTLS)
        ->addOption('Yes', 1, $useTLS);


    $valueDisplay = '';
    $classes = 'fas fa-question-circle text-warning';
    if ($adTestResult == '1') {

        $classes = 'fas fa-check-circle text-success';
    }
    $adTestResultDisplay = '<h1><i class="' . $classes . '"></i></h1>' . $valueDisplay;
    //var_dump($adTestResult);

    $adConnectionCheck->setLabel("ad Connection Test")
        ->setHtml($adTestResultDisplay)
        ->setTooltip($adTestResult);


    $adPermissionTestButton = new FormButton("Perform Check");
    $adPermissionTestButton->setLabel("ad Permission Test")
        ->setSubLabel($district->getAdBaseDN())
        ->setType("button")
        ->setId("AD_Permission_Test")
        ->addAJAXRequest('/api/district/testPerms', "AD_Permission_Test_Button_container");

    $action = new FormText('', '', 'action', 'updateDistrict');
    $action->hidden();
    $permissionsButton = new FormButton("Permissions");
    $permissionsButton->addClientRequest("/settings/district/permissions")
        ->small();

    $form->addElementToNewRow($name)
        ->addElementToNewRow($abbr)
        ->addElementToCurrentRow($action)
        ->addElementToCurrentRow($netBIOS)
        ->addElementToCurrentRow($action)
        ->addElementToNewRow($adFQDN)
        ->addElementToCurrentRow($adBaseDN)
        ->addElementToNewRow($adUsername)
        ->addElementToCurrentRow($adPassword)
        //->addElementToNewRow($adStudentGroup)
        ->addElementToNewRow($adConnectionCheck)
        ->addElementToCurrentRow($adUseTLS)
        ->addElementToCurrentRow($adPermissionTestButton);
    //->addElementToNewRow($updateButtton)

    if ($adTestResult === true) {
        $form->addElementToNewRow($permissionsButton);
    }
    echo $form->addElementToNewRow($updateButttonFloating)->print();
    ?>

    <?php
    ?>


</div>
