

<?php

use app\database\Schema;

echo $this->view('settings/district/schools/nav');
?>
<div class="">
    <?php
    //var_dump($this->school);
    ?>
    <h4>Editing <?php echo $this->school[Schema::SCHOOLS_NAME]; ?></h4>


    <form method="post" action="/schools/edit/<?php echo $this->school[Schema::SCHOOLS_ID]; ?>">
        <label for="name">Name</label>
        <input class="form-control text-center" name="<?= Schema::SCHOOLS_NAME ?>" value="<?php echo $this->school[Schema::SCHOOLS_NAME]; ?>"/>
        <label for="name">Staff Google Apps OU</label>
        <input class="form-control text-center" name="<?= Schema::SCHOOLS_STAFFGAOU ?>" value="<?php echo $this->school[Schema::SCHOOLS_STAFFGAOU]; ?>"/>
        <label for="name">Staff LDAP OU</label>
        <input class="form-control text-center" name="<?= Schema::SCHOOLS_STAFFADOU ?>" value="<?php echo $this->school[Schema::SCHOOLS_STAFFADOU]; ?>"/>

        <a href="/grades/show/<?php echo $this->school[Schema::SCHOOLS_ID]; ?>" class="my-3 btn btn-warning">Edit Grades</a><br/>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>


