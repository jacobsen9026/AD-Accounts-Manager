<div id="debugConfigModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h4 class="modal-title ">Config</h4>
                <button type="button" class="close text-light" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
                <p class="text-break "><?php
                    foreach (\system\Database::get()->getAllTables()as $table) {
                        echo $table;
                        var_dump(\system\Database::get()->query('SELECT * FROM ' . $table)[0]);
                    }
                    ?>
                </p>
            </div>

        </div>

    </div>
</div>