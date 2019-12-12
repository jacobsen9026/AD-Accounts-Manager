
<?php

function printSessionArray($array) {
    foreach ($array as $entry) {
        $entry = var_export($entry);
        ?>
        <div class='appDebugEntry'>
            <?= htmlspecialchars($entry); ?>
        </div>
        <?php
    }
}
?>





<div class="scroll">


    <?php
    if (isset($_SESSION) and sizeof($_SESSION) > 0) {
        ?>
        <div class="alert alert-info dark-shadow m-3">


            <div id='sessionContents' class=''>
                <?php
                printSessionArray($_SESSION);
                ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>


