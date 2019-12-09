
<?php

function drawLogButton($text, $targetID, $level) {
    ?>
    <button class = 'btn btn-<?php echo $level; ?> btn-block' data-toggle = "collapse" data-target = '#<?php echo $targetID; ?>'><?php echo $text; ?></button>
    <?php
}

$error = $this->core->logger->getLogs()['error'];
$warning = $this->core->logger->getLogs()['warning'];
$debug = $this->core->logger->getLogs()['debug'];
$info = $this->core->logger->getLogs()['info'];
if (!function_exists('printDebugArray')) {

    function printDebugArray($array) {
        foreach ($array as $entry) {
            $entry = explode(" ", $entry, 2);
            ?>
            <div class='appDebugEntry'>
                <div class='file'>
                    <?= htmlspecialchars($entry[0]); ?>
                </div>
                <div class='message'>
                    <?= htmlspecialchars($entry[1]); ?>
                </div>
            </div>
            <?php
        }
    }

}
?>







<div class="scroll">
    <?php
    if (isset($error) and sizeof($error) > 0) {
        ?>

        <div class="alert alert-danger dark-shadow m-3">
            <?php drawLogButton('System Error', 'systemError', 'danger'); ?>
            <div id='systemError' class='collapse '>
                <?php
                printDebugArray($error);
                ?>
            </div>
        </div>
        <?php
    }if (isset($warning) and sizeof($warning) > 0) {
        ?>

        <div class="alert alert-warning dark-shadow m-3">
            <?php drawLogButton('System Warning', 'systemWarning', 'warning'); ?>
            <div id='systemWarning' class='collapse '>
                <?php
                printDebugArray($warning);
                ?>
            </div>
        </div>
        <?php
    }if (isset($debug) and sizeof($debug) > 0) {
        ?>

        <div class="alert alert-success dark-shadow m-3">
            <?php drawLogButton('System Debug', 'systemDebug', 'success'); ?>
            <div id='systemDebug' class='collapse '>
                <?php
                printDebugArray($debug);
                ?>
            </div>
        </div>
        <?php
    }if (isset($info) and sizeof($info) > 0) {
        ?>

        <div class="alert alert-info dark-shadow m-3">
            <?php drawLogButton('System Info', 'systemInfo', 'info'); ?>
            <div id='systemInfo' class='collapse '>
                <?php
                printDebugArray($info);
                ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>

