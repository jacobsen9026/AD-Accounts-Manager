<?php
/** @var \App\Models\Domain\DomainComputer $computer */

use App\Forms\FormText;
use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\App\Forms\FormCheckbox;

$computer = $this->computer;

$getInfoButton = new FormButton("Get Info");
$script = \App\Models\View\Javascript::on($getInfoButton->getId(), 'getInfo()');
$getInfoButton->setScript($script)
    ->tiny();
$reboot = new FormButton ('Reboot');
$reboot->addAJAXRequest('/api/computers', 'responseToast', ["action" => "reboot", "target" => $computer->getName()])
    ->tiny();
$shutdown = new FormButton ('Shut Down');
$shutdown->addAJAXRequest('/api/computers', 'responseToast', ["action" => "shutdown", "target" => $computer->getName()])
    ->tiny();
$renameForm = new Form ('/computers/edit/' . $computer->getName());
$newName = new FormText('', '', 'new_name');
$newName->setPlaceholder('New Name');
$rename = new FormButton ('Rename');
$renameAction = new FormText('', '', 'action', 'rename');
$renameAction->hidden();
$rename->tiny();
$renameReboot = new FormCheckbox('', 'Reboot after renaming', 'reboot');
$renameForm->addElementToNewRow($newName)
    ->addElementToNewRow($renameReboot)
    ->addElementToCurrentRow($rename)
    ->addElementToCurrentRow($renameAction);
?>
<div class="h4"><?= $computer->getName() ?></div>
<div class="">

    <?php if ($computer->getOs() != '') {
        ?>
        <div>Operating System: <?= $computer->getOs() ?></div>
        <?php
    }
    if ($computer->getOsServicePack() != '') {
        ?>

        <div>Service Pack: <?= $computer->getOsServicePack() ?></div>
        <?php
    }
    if ($computer->getOsVersion() != '') {
        ?>
        <div>OS Version: <?= $computer->getOsVersion() ?></div>

        <?php
    } ?>


    <script>

        function getInfo() {
            $.post("/api/computers", {
                    action: 'getInfo',
                    target: '<?=$computer->getIp()?>',
                    csrfToken: '<?=Form::getCsrfToken()?>'
                }, function (data) {
                    $('#getInfo').removeClass('d-none');
                    data = JSON.parse(data);
                    console.log(data);
                    $('#Online').html(data.output.ajax.Online);
                    if (data.output.ajax.Online == "Yes") {
                        $('#onlineInfo').removeClass('d-none');


                        $('#Manufacturer').html(data.output.ajax.Manufacturer);
                        $('#Model').html(data.output.ajax.Model);
                        $('#Processor').html(data.output.ajax.Processor);
                        $('#Memory').html(data.output.ajax.Memory);
                    }
                }
            );
        }
    </script>
    <?= $getInfoButton->print() ?>
    <div class="d-none" id="getInfo">
        <div>Online:
            <div class="d-inline-block" id="Online"></div>
        </div>

        <div class="d-none" id="onlineInfo">
            <div>Manufacturer:
                <div class="d-inline-block" id="Manufacturer">
                </div>
            </div>
            <div>Model:
                <div class="d-inline-block" id="Model"></div>
            </div>
            <div>Processor:
                <div class="d-inline-block" id="Processor"></div>
            </div>
            <div>Memory:
                <div class="d-inline-block" id="Memory"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">

            <?= $reboot->print() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-9">

            <?= $renameForm->print() ?>
        </div>
    </div>

</div>
