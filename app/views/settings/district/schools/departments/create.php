

<div class="p-5">
    <form method="post" name="test" class ="table-hover" action="/settings/departments/create/<?php echo $this->schoolID; ?>">
        <div class="container container-lg">
            <div>
                Add Department
            </div>
            <?php formTextInput('Department Name', \app\database\Schema::DEPARTMENT_NAME, ''); ?>

            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>
</div>