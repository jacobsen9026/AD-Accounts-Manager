<div id="deleteDistrictModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-warning text-light">
                <h4 class="modal-title ">Confirm District Deletion</h4>
                <button type="button" class="close text-light" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
                <p class="px-5 pb-2">All district configuration including, schools, grades, OU mappings,
                    and any other district specific data will be erased.
                    Please ensure you have a recent backup of the application configuration.
                </p>
                <a class="btn btn-danger" aria-label="Delete" href="/districts/delete/<?php echo $this->district["ID"]; ?>">
                    Delete
                </a>
            </div>

        </div>

    </div>
</div>