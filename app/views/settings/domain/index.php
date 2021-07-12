<?php

use System\Lang;

?>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a
                    class="nav-link active"
                    id="setup-tab" data-toggle="tab" href="#setup" role="tab" aria-controls="setup"
                    aria-selected="true"><?= Lang::get('Domain Setup') ?></a>
        </li>
        <li class="nav-item">
            <a
                    class="nav-link"
                    id="defaults-tab" data-toggle="tab" href="#defaults" role="tab" aria-controls="defaults"
                    aria-selected="false"><?= Lang::get('Defaults') ?></a>
        </li>


    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="setup" role="tabpanel" aria-labelledby="setup-tab">
            <?php echo $this->view('/settings/domain/show') ?>
        </div>
        <div class="tab-pane fade" id="defaults" role="tabpanel" aria-labelledby="defaults-tab">

        </div>

    </div>
<?php
