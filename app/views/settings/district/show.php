<?php
/* @var $district District */

use system\app\forms\Form;
use app\api\GAM;
use system\Encryption;
use app\models\district\District;
use system\app\forms\FormButton;
use system\app\forms\FormText;
use system\app\forms\FormHTML;

echo $this->modal('deleteDistrict');


/* @var $district District  */
/** @var District $district */
$district = $this->district;
$ad = new app\api\AD($district->getID());
$adTestResult = $ad->getConnectionResult();

//$clientSecretExists = GAM::get()->clientSecretExists();
//$google = GAM::get();
//var_dump($adTestResult);
?>

<div  id="districtOutput" class = "container-fluid">
    <div class='shadow p-3 mt-0'>
        <div class = "mb-3">
            <strong>Editing
                <?= $district->getName() ?>
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
        $form = new Form('/settings/district/edit/' . $district->getId(), 'editDistrict');
        $updateButtton = new FormButton("Update District");
        $updateButtton->medium()
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
                ->setSubLabel($district->getAdBaseDN())
                ->setType("button")
                ->setId("AD_Permission_Test")
                ->addAJAXRequest('/api/district/testPerms', "AD_Permission_Test");

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
                ->addElementToNewRow($adStudentGroup)
                ->addElementToCurrentRow($adConnectionCheck)
                ->addElementToCurrentRow($adStaffGroup)
                ->addElementToCurrentRow($adPermissionTestButton)
                ->addElementToNewRow($updateButtton);
        if ($adTestResult === true) {
            $form->addElementToNewRow($permissionsButton);
        }
        echo $form->print();
        ?>
    </div>

    <?php
    ?>





</div>
