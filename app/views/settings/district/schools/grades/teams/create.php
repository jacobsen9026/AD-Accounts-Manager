

<div class="p-5">
    <form method="post" name="test" class ="table-hover" action="/settings/teams/create/<?php echo $this->gradeID; ?>">
        <div class="container container-lg">
            <div>
                Add Team
            </div>
            <?php formTextInput('Team Name', \app\database\Schema::TEAM_NAME, ''); ?>

            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>
</div>