<?php

use app\database\Schema;
?>

<div id="deleteTeam<?php echo $this->team[Schema::TEAM_ID[Schema::COLUMN]]; ?>Modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-warning text-light">
                <h4 class="modal-title ">Confirm Team Deletion</h4>
                <button type="button" class="close text-light" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body text-center">
                <p class="px-5 pb-2">Are you sure you want to delete Team <?php echo $this->team[Schema::TEAM_NAME[Schema::COLUMN]]; ?>?<br/>
                    All team configuration including OU mappings,
                    and any other team specific data will be erased.
                    Please ensure you have a recent backup of the application configuration.
                </p>
                <a class="btn btn-danger" aria-label="Delete" href="/settings/teams/delete/<?php echo $this->team[Schema::TEAM_ID[Schema::COLUMN]]; ?>">
                    Delete
                </a>
            </div>

        </div>

    </div>
</div>