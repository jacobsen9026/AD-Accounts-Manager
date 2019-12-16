
<?php
$log = $this->core->logger->getLog();

if (!function_exists('printLog')) {

    function printSystemLog($array) {
        foreach ($array as $entry) {
            //var_dump($entry);
            $message = $entry[2];
            $et = substr(strval($entry[0]), 0, 5);
            $level = $entry[1];
            switch ($level) {
                case 'error':
                    $alertLevel = 'danger';

                    break;
                case 'debug':
                    $alertLevel = 'success';

                    break;


                default:
                    $alertLevel = $level;
                    break;
            }
            ?>
            <div class=' collapse show container mx-auto my-0 py-1 rounded-0 row alert alert-<?php echo $alertLevel; ?> <?php echo $level; ?>SystemLogEntry'>
                <div class='col-1'>
                    <?= htmlspecialchars($et); ?>
                </div>
                <div class='col-11 text-break'>
                    <?= htmlspecialchars($message); ?>
                </div>
            </div>
            <?php
        }
    }

}
?>







<div class="scroll">
    <?php
    if (isset($log) and sizeof($log) > 0) {
        ?>

        <div class ="sticky-top log-nav row p-0 container text-center mx-auto mb-0">
            <button class = 'col rounded-0 btn btn-info' data-toggle = "collapse" data-target = '.infoSystemLogEntry' data-text-alt="Show Info">Hide Info</button>
            <button class = 'col rounded-0 btn btn-success' data-toggle = "collapse" data-target = '.debugSystemLogEntry' data-text-alt="Show Debug">Hide Debug</button>
            <button class = 'col rounded-0 btn btn-warning' data-toggle = "collapse" data-target = '.warningSystemLogEntry' data-text-alt="Show Warning">Hide Warning</button>
            <button class = 'col rounded-0 btn btn-danger' data-toggle = "collapse" data-target = '.errorSystemLogEntry' data-text-alt="Show Error">Hide Error</button>
        </div>
        <div id='systemLog'>
            <?php
            printSystemLog($log);
            ?>
        </div>
        <?php
    }
    ?>
</div>

