<?php
/* @var $district District */

use app\database\Schema;

echo $this->modal('deleteDistrict');
$grades = array('PK3', 'PK4', 'K', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
?>

<?= $this->view('settings/district/schools/nav'); ?>
<div class = "container p-5">

    <div class = "mb-3">
        <strong>Editing
            <?php
            echo $this->district[Schema::DISTRICTS_NAME];
            //var_dump($this->district);
            ?>
        </strong>
        <button type="button" class="close text-danger" aria-label="Close" data-toggle="modal" data-target="#deleteDistrictModal">
            <span aria-hidden="true">&times;</span>
        </button>

    </div>

    <form method = "post" class = "table-hover" action = "/districts/edit">
        <div class = "">
            <div class="row">
                <div class="col-md">
                    <?php
                    formTextInput('Name', Schema::DISTRICTS_NAME, $this->district[Schema::DISTRICTS_NAME]);
                    formTextInput('Abbreviation', Schema::DISTRICTS_ABBREVIATION, $this->district[Schema::DISTRICTS_ABBREVIATION]);
                    formTextInput('Active Directory FQDN', Schema::DISTRICTS_AD_FQDN, $this->district[Schema::DISTRICTS_AD_FQDN]);
                    ?>
                </div>
                <div class="col-md">
                    <?php
                    formTextInput('Google Apps FQDN', Schema::DISTRICTS_GA_FQDN, $this->district[Schema::DISTRICTS_GA_FQDN]);
                    formTextInput('Active Directory Domain NetBIOS', Schema::DISTRICTS_AD_NETBIOS, $this->district[Schema::DISTRICTS_AD_NETBIOS]);

                    formTextInput('District Parent Email Group', Schema::DISTRICTS_PARENT_EMAIL_GROUP,
                            $this->district[Schema::DISTRICTS_PARENT_EMAIL_GROUP], 'Do not enter the domain name.');
                    ?>
                </div>
            </div>
            <?php
            echo $this->view('settings/district/nav');

            formUpdateButton();
            ?>

            <h5 class="mt-5">
                Staff
            </h5>
            <div class="row pt-3">

                <div class="col-md">

                    <?php
                    formTextInput('Staff Active Directory OU', Schema::DISTRICTS_STAFF_AD_OU, $this->district[Schema::DISTRICTS_STAFF_AD_OU]);
                    formTextInput('Staff Google Apps OU', Schema::DISTRICTS_STAFF_GA_OU, $this->district[Schema::DISTRICTS_STAFF_GA_OU]);
                    ?>
                </div>
                <div class="col-md">

                    <?php
                    formTextInput('Staff Active Directory Group', Schema::DISTRICTS_STAFF_AD_GROUP, $this->district[Schema::DISTRICTS_STAFF_AD_GROUP]);

                    formTextInput('Staff Google Apps Group', Schema::DISTRICTS_STAFF_GA_GROUP, $this->district[Schema::DISTRICTS_STAFF_GA_GROUP]);
                    ?>
                </div>
            </div>
            <div class="row">

                <div class="col-md">
                    <?php
                    formTextInput('Staff Active Directory Home Drive Path', Schema::DISTRICTS_STAFF_AD_GROUP, $this->district[Schema::DISTRICTS_STAFF_AD_GROUP]);

                    formTextInput('Staff Active Directory Logon Script', Schema::DISTRICTS_STAFF_GA_GROUP, $this->district[Schema::DISTRICTS_STAFF_GA_GROUP]);
                    ?>
                </div>
            </div>
        </div>
        <?php formUpdateButton(); ?>

        <input hidden type="hidden" name="<?= Schema::DISTRICTS_ID ?>" value="<?php echo $this->district[Schema::DISTRICTS_ID]; ?>"/>

    </form>
</div>


