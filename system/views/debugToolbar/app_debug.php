<?php
$log = $this->appLogger->getLogs();

if (!function_exists('printLog')) {

    function printAppLog($array)
    {
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
                    <p data-toggle="collapse" data-target="#appLogBacktrace<?= $logIndex ?>" aria-expanded="false"
                       aria-controls="appLogBacktrace<?= $logIndex ?>">
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

        <div class="sticky-top log-nav row p-0 text-center mx-auto mb-0">
            <button class='col rounded-0 btn btn-info' data-toggle="collapse" data-target='.infoAppLogEntry'
                    data-text-alt="Show Info">Hide Info
            </button>
            <button class='col rounded-0 btn btn-success' data-toggle="collapse" data-target='.debugAppLogEntry'
                    data-text-alt="Show Debug">Hide Debug
            </button>
            <button class='col rounded-0 btn btn-warning' data-toggle="collapse" data-target='.warningAppLogEntry'
                    data-text-alt="Show Warning">Hide Warning
            </button>
            <button class='col rounded-0 btn btn-danger' data-toggle="collapse" data-target='.errorAppLogEntry'
                    data-text-alt="Show Error">Hide Error
            </button>
        </div>
        <div id='systemLog'>
            <?php
            printAppLog($log);
            ?>
        </div>
        <?php
    }
    ?>
</div>

