<?php

use app\database\Schema;
?>

<form method = "post" class = "table-hover" action = "/schools/create/<?php echo $this->districtID; ?>">
    <div class = "container container-lg">
        <div>
            Create School
        </div>
        <div>Name:<input type = "text" name = "<?= Schema::SCHOOLS_NAME ?>"/></div>
        <button class = "btn btn-primary" type = "submit">Submit</button>
    </div>
</form>