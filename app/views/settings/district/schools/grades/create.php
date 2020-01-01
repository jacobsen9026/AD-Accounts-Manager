
<?php

use app\database\Schema;

if (empty($this->grades)) {

    echo $this->view('layouts/setup_navbar');
}
?>
<div class="p-5">
    <form method="post" name="test" class ="table-hover" action="/settings/grades/create/<?php echo $this->schoolID; ?>">
        <div class="container container-lg">

            <div>
                Add Grade
            </div>
            <div>Grade Level:

                <select name="<?= Schema::GRADEDEFINITION_VALUE[Schema::NAME]; ?>">


                    <?php
                    foreach ($this->gradeDefinitions as $this->gradeDefinition) {
                        //var_dump($this->gradeDefinition);
                        ?>
                        <option value="<?= $this->gradeDefinition[Schema::GRADEDEFINITION_VALUE[Schema::COLUMN]] ?> ">
                            <?= $this->gradeDefinition[Schema::GRADEDEFINITION_DISPLAY_CODE[Schema::COLUMN]] ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>
</div>