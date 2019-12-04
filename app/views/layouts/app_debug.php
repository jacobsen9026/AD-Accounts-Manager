<style>


</style>
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





<div class="appTab tab">
    <div class="scroll">
        <?php
        if (isset($error) and sizeof($error) > 0) {
            ?>
            <div class="appDebugContainer appError">
                <div><strong>App Error</strong></div>
                <div>
                    <?php
                    printAppDebugArray($error);
                    ?>
                </div>
            </div>
            <?php
        }if (isset($warning) and sizeof($warning) > 0) {
            ?>
            <div class="appDebugContainer appWarning">
                <div><strong>App Warning</strong></div>
                <div>
                    <?php
                    printAppDebugArray($warning);
                    ?>
                </div>
            </div>
            <?php
        }if (isset($debug) and sizeof($debug) > 0) {
            ?>
            <div class="appDebugContainer appDebug">
                <div><strong>App Debug</strong></div>
                <div>
                    <?php
                    printAppDebugArray($debug);
                    ?>
                </div>
            </div>
            <?php
        }if (isset($info) and sizeof($info) > 0) {
            ?>
            <div class="appDebugContainer appInfo">
                <div><strong>App Info</strong></div>
                <div>
                    <?php
                    printAppDebugArray($info);
                    ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

</div>

