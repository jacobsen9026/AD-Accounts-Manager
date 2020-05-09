
<div id="debugConfigModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h4 class="modal-title ">Config</h4>
                <button type="button" class="close text-light" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
                <div id="ajaxOutput"><!--<span class="spinner-border text-primary" role="status"></span><br/>Loading Configuration Data... --></div>
                <script>
<?php

use app\models\view\Javascript;

$ajax = Javascript::buildAJAXRequest('/api/app', 'ajaxOutput', ["action" => \app\controllers\api\App::GET_CONFIG], true);
$onClick = Javascript::onClick("debugConfigButton", $ajax);
echo $onClick;
?>


                </script>

                <?php
                /**
                  foreach (\system\Database::get()->getAllTables()as $table) {
                  echo $table;
                  var_dump(\system\Database::get()->query('SELECT * FROM ' . $table)[0]);
                  }
                 *
                 */
                ?>

            </div>

        </div>

    </div>
</div>