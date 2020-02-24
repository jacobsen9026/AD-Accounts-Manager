
<?php
if (isset($this->appLogger)) {
    $log = $this->appLogger->getLog('query');
}
if (!function_exists('printQueryLog')) {

    function printQueryLog($array) {
        $logIndex = 0;
        foreach ($array as $entry) {

            $logIndex++;
            //var_dump($entry);
            $message = $entry[2];
            $backTrace = $entry[3];
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
            <div class=' collapse show container mx-auto my-0 py-1 row rounded-0 alert alert-<?php echo $alertLevel; ?> <?php echo $level; ?>AppLogEntry'>
                <div class='col-1 '>
                    <?= htmlspecialchars($et); ?>
                </div>
                <div class='col-11 text-break'>
                    <?php //htmlspecialchars($message);     ?>
                    <p data-toggle="collapse" data-target="#appLogBacktrace<?= $logIndex ?>" aria-expanded="false" aria-controls="appLogBacktrace<?= $logIndex ?>">
                        <?= $message; ?>
                    </p>
                    <div class="collapse" id="appLogBacktrace<?= $logIndex ?>">
                        <?php
                        foreach ($backTrace as $line) {
                            echo $line;
                        }
                        ?>
                    </div>
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


        <div id=''>
            <?php
            printQueryLog($log);
            ?>
        </div>
        <?php
    }
    ?>
</div>

