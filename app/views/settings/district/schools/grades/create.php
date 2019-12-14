
<?php
if (empty($this->grades)) {
    echo $this->view('settings/district/schools/nav');
}
?>
<form method="post" name="test" class ="table-hover" action="/grades/create/<?php echo $this->schoolID; ?>">
    <div class="container container-lg">
        <div>
            Add Grade
        </div>
        <div>Grade Level:
            <select name="level">

                <?php
                foreach (GRADE_CODES as $grade) {
                    ?>
                    <option value ="<?php echo $grade; ?>"><?php echo $grade; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <button class="btn btn-primary" type="submit">Submit</button>
    </div>
</form>