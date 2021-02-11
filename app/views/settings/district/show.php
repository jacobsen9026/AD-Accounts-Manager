<?php
/* @var $domain Domain */

use App\Api\Ad\ADConnection;
use App\Models\Database\DomainDatabase;
use App\Forms\FormText;
use System\App\Forms\Form;
use System\App\Forms\FormFloatingButton;
use System\Encryption;
use App\Models\District\Domain;
use System\App\Forms\FormButton;
use System\App\Forms\FormHTML;


/* @var $district Domain */
/** @var Domain $district */
$domain = $this->domain;

$adTestResult = ADConnection::isConnected();
if (!$adTestResult) {
    $adTestResult = ADConnection::getError();
}
//$clientSecretExists = GAM::get()->clientSecretExists();
//$google = GAM::get();
//var_dump($adTestResult);
?>

<div id="districtOutput" class="container-fluid bg-white shadow p-3 mt-0 pb-5 mb-5">

    <div class="mb-3">
        <strong>Editing
            <?= $domain->getName() ?>
        </strong>


    </div>


    <?php
    $form = new Form('/settings/domain/edit/' . $domain->getId(), 'editDistrict');

    /**
     * @deprecated
     */
    $updateButtton = new FormButton("Update District");
    $updateButtton->medium()
        ->addAJAXRequest('/api/settings/domain', 'districtOutput', $form);


    $updateButttonFloating = new FormFloatingButton('<i class="h3 mb-0 fas fa-check"></i>');
    $updateButttonFloating->setId('floatingSaveButton')
        ->addAJAXRequest('/api/settings/domain', 'districtOutput', $form);

    $name = new FormText("Name", "", "name", $domain->getName());
    $name->full();
    $action = new FormText('', '', 'aciton', 'updateDistrict');
    $action->hidden();
    $abbr = new FormText("Abbreviation", "", "abbr", $domain->getAbbr());
    $netBIOS = new FormText("Active Directory Domain NetBIOS", "", "adNetBIOS", $domain->getAdNetBIOS());
    $adFQDN = new FormText("Active Directory FQDN", "", "adFQDN", $domain->getAdFQDN());
    $adBaseDN = new FormText("Active Directory Base DN", "The point from which the application searches from.", "adBaseDN", $domain->getAdBaseDN());
    $adUsername = new FormText("Active Directory Username", "Enter an account with admin privileges to the district OU's", "adUsername", $domain->getAdUsername());
    $adPassword = new FormText("Active Directory Password", "Enter password for admin user", "adPassword", Encryption::encrypt($domain->getAdPassword()));
    $adPassword->isPassword();
    //$adStudentGroup = new FormText("Active Directory Student Group", "This group should contain all active and inactive students as well as all student groups", "adStudentGroup", $district->getAdStudentGroupName());
    //$adStaffGroup = new FormText("Active Directory Staff Group", "This group should contain all active staff as well as all staff groups", "adStaffGroup", $district->getAdStaffGroupName());

    $adConnectionCheck = new FormHTML();
    $useTLS = DomainDatabase::getAD_UseTLS();
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

    $adConnectionCheck->setLabel("AD Connection Test")
        ->setHtml($adTestResultDisplay)
        ->setTooltip($adTestResult);


    $adPermissionTestButton = new FormButton("Perform Check");
    $adPermissionTestButton->setLabel("AD Permission Test")
        ->setSubLabel($domain->getAdBaseDN())
        ->setType("button")
        ->small()
        ->setId("AD_Permission_Test")
        ->addAJAXRequest('/api/settings/domain/testADPermissions', "AD_Permission_Test_Button_container", ["csrfToken" => Form::getCsrfToken()]);

    $action = new FormText('', '', 'action', 'updateDistrict');
    $action->hidden();
    $permissionsButton = new FormButton("Permissions");
    $permissionsButton->addClientRequest("/settings/domain/permissions")
        ->medium();

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
