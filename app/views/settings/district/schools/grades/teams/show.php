
<?php

use app\database\Schema;

echo $this->view('layouts/setup_navbar');
if (!empty($this->teams)) {
    ?>

    <div class="p-5">
        <h4>Teams under Grade <?php // echo $this->grade[Schema::GRADE_VALUE[Schema::COLUMN]];       ?></h4>

        <div class="table-responsive-sm">

            <table class="mx-auto table table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Team</th>
                        <th>Edit</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($this->teams as $this->team) {
                        //var_dump($school);
                        ?>
                        <tr>
                            <td>
                                <?php echo $this->team[Schema::TEAM_NAME[Schema::COLUMN]]; ?>
                            </td>


                            <td>
                                <a href="/settings/teams/edit/<?php echo $this->team[Schema::TEAM_ID[Schema::COLUMN]]; ?>"  class = "btn btn-warning">Edit Team</a>
                            </td>
                            <td>
                                <?php echo $this->view('modals/deleteTeam'); ?>
                                <button type="button" class = "btn btn-danger" data-toggle="modal" data-target="#deleteTeam<?php echo $this->team[Schema::TEAM_ID[Schema::COLUMN]]; ?>Modal">Remove Team</button>

                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>



            </table>
        </div>
        <?php
    }
    echo $this->view('settings/district/schools/grades/teams/create');
    ?>
</div>
