
<?php

use app\database\Schema;

echo $this->view('layouts/setup_navbar');
if (!empty($this->departments)) {
    ?>

    <div class="p-5">
        <h4>Departments under <?php echo $this->schoolName; ?></h4>

        <div class="table-responsive-sm">

            <table class="mx-auto table table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Department</th>
                        <th>Edit</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($this->departments as $this->department) {
                        //var_dump($school);
                        ?>
                        <tr>
                            <td>
                                <?php echo $this->department[Schema::DEPARTMENT_NAME[Schema::COLUMN]]; ?>
                            </td>


                            <td>
                                <a href="/settings/departments/edit/<?php echo $this->department[Schema::DEPARTMENT_ID[Schema::COLUMN]]; ?>"  class = "btn btn-warning">Edit Department</a>
                            </td>
                            <td>
                                <?php echo $this->view('modals/deleteDepartment'); ?>
                                <button type="button" class = "btn btn-danger" data-toggle="modal" data-target="#deleteDepartment<?php echo $this->department[Schema::DEPARTMENT_ID[Schema::COLUMN]]; ?>Modal">Remove Department</button>

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
    echo $this->view('settings/district/schools/departments/create');
    ?>
</div>
