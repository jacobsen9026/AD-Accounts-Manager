
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
    <h4>Schools in <?= $this->district[Schema::DISTRICTS_NAME]; ?></h4>

    <div class="table-responsive-sm shadow-sm">

        <table class="mx-auto table table-hover tablesorter">
            <thead class="bg-primary text-white">
                <tr>
                    <th class="sorter-true">School Name <i class="float-right fas fa-caret-up"></i></th>

                    <th class="sorter-false">Edit</th>
                    <th class="sorter-false">Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($this->schools as $this->school) {
                    //var_dump($school);
                    ?>
                    <tr data-id="<?php echo $this->school[Schema::SCHOOLS_ID]; ?>">
                        <td>
                            <i class="fas fa-school"></i>
                            <i class="fas fa-building"></i>
                            <?php echo $this->school[Schema::SCHOOLS_NAME]; ?>
                        </td>


                        <td>
                            <a href="/schools/edit/<?php echo $this->school[Schema::SCHOOLS_ID]; ?>"  class = "btn btn-warning">Edit School</a>
                        </td>
                        <td>
                            <?php echo $this->view('modals/deleteSchool'); ?>
                            <button type="button" class = "btn btn-danger" data-toggle="modal" data-target="#deleteSchool<?php echo $this->school[Schema::SCHOOLS_ID]; ?>Modal">Remove School</button>

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
