
<?php
$error = $this->appLogger->getLogs()['error'];
$warning = $this->appLogger->getLogs()['warning'];
$debug = $this->appLogger->getLogs()['debug'];
$info = $this->appLogger->getLogs()['info'];

function printAppDebugArray($array) {
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
?>





<div class="scroll">
    <?php
    if (isset($error) and sizeof($error) > 0) {
        ?>
        <div class="alert alert-danger dark-shadow m-3">
            <?php drawLogButton('App Error', 'appError', 'danger'); ?>

            <div id='appError' class='collapse'>
                <?php
                printAppDebugArray($error);
                ?>
            </div>
        </div>
        <?php
    }if (isset($warning) and sizeof($warning) > 0) {
        ?>
        <div class="alert alert-warning dark-shadow m-3">
            <?php drawLogButton('App Warning', 'appWarning', 'warning'); ?>
            <div id='appWarning' class='collapse'>
                <?php
                printAppDebugArray($warning);
                ?>
            </div>
        </div>
        <?php
    }if (isset($debug) and sizeof($debug) > 0) {
        ?>
        <div class="alert alert-success dark-shadow m-3">
            <?php drawLogButton('App Debug', 'appDebug', 'success'); ?>
            <div id='appDebug' class='collapse'>
                <?php
                printAppDebugArray($debug);
                ?>
            </div>
        </div>
        <?php
    }if (isset($info) and sizeof($info) > 0) {
        ?>
        <div class="alert alert-info dark-shadow m-3">
            <?php drawLogButton('App Info', 'appInfo', 'info'); ?>
            <div id='appInfo' class='collapse '>
                <?php
                printAppDebugArray($info);
                ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>


