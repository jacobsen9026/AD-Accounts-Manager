<?php

use app\database\Schema;
?>

<form method = "post" class = "table-hover" action = "/schools/create/<?php echo $this->districtID; ?>">
    <div class = "border mt-3 container container-lg py-3 bg-light shadow-sm">
        <h5>
            Create School
        </h5>
        <?php formTextInput('Name', Schema::SCHOOL_NAME[Schema::NAME], null);
        ?>

        <button class = "btn btn-primary" type = "submit">Submit</button>
    </div>
</form>