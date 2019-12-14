
<?php

use app\database\Schema;

echo $this->view('settings/district/schools/nav');
?>


<script type="text/javascript">
    $(function () {
        $("tbody").sortable();

    });
    function saveOrder() {
        var schoolOrder = $("tbody").sortable('toArray', {
            attribute: 'data-id',
        });


        console.log('Manipulated Order:' + schoolOrder);
        schoolOrder.forEach(function (item, index, array) {
            array[index] = item * 10;
        });
        console.log('IntermediateOrder' + schoolOrder);
        // var xhr = new XMLHttpRequest();
        // xhr.open("POST", yourUrl, true);
        // xhr.setRequestHeader('Content-Type', 'application/json');
        // xhr.send(JSON.stringify({
        //     value: value
        // }));
    }
</script>

<div class="">
    <h4>Schools in <?php $this->district[Schema::DISTRICT_NAME]; ?></h4>

    <div class="table-responsive-sm">

        <table class="mx-auto table table-hover">
            <thead class="bg-primary text-white">
                <tr>
                    <th>School Name</th>

                    <th>Edit</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($this->schools as $this->school) {
                    //var_dump($school);
                    ?>
                    <tr data-id="<?php echo $this->school[Schema::SCHOOLS_ID]; ?>">
                        <td>
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
    <button class = "btn btn-info" type = "button" onclick="saveOrder()">Update Order</button>
    <form method = "post" name = "test" class = "table-hover" action = "/schools/create/<?php echo $this->districtID; ?>"><input name = "name" type = "text"/>
        <button class = "btn btn-info" type = "submit">Add School</button>
    </form>
</div>
