
<?php

use app\database\Schema;

echo $this->view('settings/district/schools/nav');
?>
<script>
    $(function () {
        $("table").tablesorter({sortList: [[0, 0]]});
        $(".sorter-true").on("click", function () {
            $('.sorter-true i').toggleClass("fa-caret-up fa-caret-down");
        });
    });
</script>
<div class="p-5">
    <h4>Grades at <?php echo $this->school[Schema::SCHOOLS_NAME]; ?></h4>

    <div class="table-responsive-sm">

        <table class="mx-auto table table-hover">
            <thead class="bg-primary text-white">
                <tr>
                    <th class="sorter-true">Grade Level <i class="float-right fas fa-caret-up"></i></th>
                    <th class="sorter-false">Edit</th>
                    <th class="sorter-false">Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($this->grades as $this->grade) {
                    //var_dump($school);
                    ?>
                    <tr>
                        <td>
                            <?php echo $this->grade["Level"]; ?>
                        </td>


                        <td>
                            <a href="/grades/edit/<?php echo $this->grade["ID"]; ?>"  class = "btn btn-warning">Edit Grade</a>
                        </td>
                        <td>
                            <?php echo $this->view('modals/deleteGrade'); ?>
                            <button type="button" class = "btn btn-danger" data-toggle="modal" data-target="#deleteGrade<?php echo $this->grade["ID"]; ?>Modal">Remove Grade</button>

                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>



        </table>
    </div>
    <?php
    echo $this->view('settings/district/schools/grades/create');
    ?>
</div>
