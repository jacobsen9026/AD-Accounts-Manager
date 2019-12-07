<div id="debugConfigModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h4 class="modal-title ">Config</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
                <p><?php echo $this->varDump($this->app->config); ?>
                </p>
            </div>

        </div>

    </div>
</div>