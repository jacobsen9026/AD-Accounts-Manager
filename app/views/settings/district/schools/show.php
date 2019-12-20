
<?php

use app\database\Schema;
use app\models\district\Grade;
?>


<?= $this->view('layouts/setup_navbar'); ?>
<div class = "container  px-md-5">
    <script>
        $(function () {
            $("table").tablesorter({sortList: [[0, 0]]});
            $(".sorter-true").on("click", function () {
                $('.sorter-true i').toggleClass("fa-caret-up fa-caret-down");
            });
        });
    </script>
    <h4>Schools in <?= $this->district[Schema::DISTRICT_NAME[Schema::COLUMN]]; ?></h4>

    <div class="table-responsive-sm shadow-sm">

        <table class="mx-auto table table-hover tablesorter">
            <thead class="bg-primary text-white">
                <tr>
                    <th class="sorter-false">Type</th>

                    <th class="sorter-true">School Name <i class="float-right fas fa-caret-up"></i></th>

                    <th class="sorter-false">Edit</th>
                    <th class="sorter-false">Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($this->schools as $this->school) {
                    //var_dump($school);
                    $schoolID = $this->school[Schema::SCHOOL_ID[Schema::COLUMN]];
                    ?>

                    <tr data-id="<?php echo $schoolID; ?>">
                        <td>
                            <?php
                            $grades = Grade::getGrades($schoolID);
                            //var_dump($grades);
                            if (isset($grades) and $grades != false and count($grades) > 0) {
                                //var_dump("TRUE");
                                echo '<i class="fas fa-school"></i>';
                            } else {
                                //var_dump("FALSE");
                                echo '<i class="fas fa-building"></i>';
                            }
                            ?>

                        </td>
                        <td>


                            <?php echo $this->school[Schema::SCHOOL_NAME[Schema::COLUMN]]; ?>
                        </td>


                        <td>
                            <a href="/schools/edit/<?php echo $this->school[Schema::SCHOOL_ID[Schema::COLUMN]]; ?>"  class = "btn btn-warning">Edit School</a>
                        </td>
                        <td>
                            <?php echo $this->view('modals/deleteSchool'); ?>
                            <button type="button" class = "btn btn-danger" data-toggle="modal" data-target="#deleteSchool<?php echo $this->school[Schema::SCHOOL_ID[Schema::COLUMN]]; ?>Modal">Remove School</button>

                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>



        </table>
    </div>
    <!--
    <button class = "btn btn-info" type = "button" onclick="saveOrder()">Update Order</button>
    -->
    <?php
    echo $this->view('settings/district/schools/create');
    ?>
</div>