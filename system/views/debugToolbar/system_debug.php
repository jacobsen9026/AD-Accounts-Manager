<?php

use System\SystemLogger;
use System\Models\View\LogPrinter;

/* @var $logger SystemLogger */
$logger = $this->logger;
$log = $this->logger->getLogs();
?>
<script>


    $('#toggleInfo').on('click', function () {
        console.log('works');
        if ($('.infoSystemLogEntry').css('visibility') == 'hidden')
            $('.infoSystemLogEntry').css('visibility', 'visible');
        else
            $('.infoSystemLogEntry').css('visibility', 'hidden');
    });


</script>


<div class="scroll">
    <?php
    if (isset($log) and sizeof($log) > 0) {
        ?>

        <div class="sticky-top log-nav row p-0 text-center mx-auto mb-0">
            <button id="toggleInfo" class='col rounded-0 btn btn-info' data-toggle="collapse"
                    data-target='.infoSystemLogEntry' data-text-alt="Show Info">Hide Info
            </button>
            <button class='col rounded-0 btn btn-success' data-toggle="collapse" data-target='.debugSystemLogEntry'
                    data-text-alt="Show Debug">Hide Debug
            </button>
            <button class='col rounded-0 btn btn-warning' data-toggle="collapse" data-target='.warningSystemLogEntry'
                    data-text-alt="Show Warning">Hide Warning
            </button>
            <button class='col rounded-0 btn btn-danger' data-toggle="collapse" data-target='.errorSystemLogEntry'
                    data-text-alt="Show Error">Hide Error
            </button>
        </div>

        <?php
        $logEntries = $logger->getLogEntries();
        //var_dump($logEntries);
//LogPrinter::printLogEntries($log);
        echo LogPrinter::printLog($logger);
        //printSystemLog($log);
        ?>

        <?php
    }
    ?>
</div>

