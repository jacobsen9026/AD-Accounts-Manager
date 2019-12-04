
<?php
$error = $this->core->logger->getLogs()['error'];
$warning = $this->core->logger->getLogs()['warning'];
$debug = $this->core->logger->getLogs()['debug'];
$info = $this->core->logger->getLogs()['info'];

function printDebugArray($array) {
    foreach ($array as $entry) {
        $entry = explode(" ", $entry, 2);
        ?>
        <div class='systemDebugEntry'>
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






<div class="systemTab tab">
    <div class="scroll">
        <?php
        if (isset($error) and sizeof($error) > 0) {
            ?>
            <div class="systemDebugContainer systemError">
                <div><strong>System Error</strong></div>
                <div>
                    <?php
                    printDebugArray($error);
                    ?>
                </div>
            </div>
            <?php
        }if (isset($warning) and sizeof($warning) > 0) {
            ?>
            <div class="systemDebugContainer systemWarning">
                <div><strong>System Warning</strong></div>
                <div>
                    <?php
                    printDebugArray($warning);
                    ?>
                </div>
            </div>
            <?php
        }if (isset($debug) and sizeof($debug) > 0) {
            ?>
            <div class="systemDebugContainer systemDebug">
                <div><strong>System Debug</strong></div>
                <div>
                    <?php
                    printDebugArray($debug);
                    ?>
                </div>
            </div>
            <?php
        }if (isset($info) and sizeof($info) > 0) {
            ?>
            <div class="systemDebugContainer systemInfo">
                <div><strong>System Info</strong></div>
                <div>
                    <?php
                    printDebugArray($info);
                    ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

