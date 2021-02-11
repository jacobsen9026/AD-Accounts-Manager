<?php


?>
    <script>

        $(document).on('click', '.ouLocationButton[data-target-ou]', function () {

            let ou = $(this).data('target-ou');
            if (confirm("Move to " + ou + "?")) {
                $('#ouInput').attr('value', ou);
                $('form[name=moveToOU]').submit();
                console.log("moving object to: " + ou);

            }


        });
        $(document).on('click', '.ouLocationButton:not([data-target-ou])', function () {
            $('#noPermissionToLocation').toast('show');
        });

    </script>
<?php
/** @var array $params */

use App\Forms\FormText;
use App\Models\View\Toast;
use System\App\Forms\Form;

/** @var \App\Models\District\DomainUser $object */
$object = $params['object'];
$form = new Form('/users/edit/' . $object->activeDirectory->getAccountName(), 'moveToOU');
$action = new FormText("action");
$action->hidden()
    ->setName("action")
    ->setValue("moveToOU");

$dn = new FormText('dn', 'dn', 'dn', $object->getDistinguishedName());
$dn->hidden();
$ou = new FormText('ou', 'ou', 'ou', '');
$ou->hidden();
$form->addElementToNewRow($dn)
    ->addElementToNewRow($ou)
    ->addElementToNewRow($action);
echo $form->print();
$toast = new Toast('No Permission',
    'You don\'t have permission to that location',
    1000);
$toast->setId('noPermissionToLocation');
echo $toast->printToast();

