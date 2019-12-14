
<div class=" mb-3 rounded row">
    <div class="col px-1 d-flex align-items-stretch ">
        <a class="w-100 rounded-0 nav-link btn btn-success" href="/districts/">Back to District</a>

    </div>
    <?php
    if (isset($this->schoolID)) {
        ?>
        <div class="col px-1 d-flex align-items-stretch ">
            <a class="w-100 rounded-0 nav-link btn btn-success" href="/schools/show/<?php echo $this->districtID; ?>">Back to Schools</a>

        </div>
        <?php
    }
    if (isset($this->grades)) {
        ?>
        <div class="col px-1 d-flex align-items-stretch ">
            <a class="w-100 rounded-0 nav-link btn btn-success" href="/schools/edit/<?php echo $this->schoolID; ?>">Back to <?php echo $this->schoolName; ?></a>

        </div>
        <?php
    }
    if (isset($this->gradeID)) {
        ?>
        <div class="col px-1 d-flex align-items-stretch ">
            <a class="w-100 rounded-0 nav-link btn btn-success" href="/grades/show/<?php echo $this->schoolID; ?>">Back to Grades</a>

        </div>
        <?php
    }
    ?>


</div>