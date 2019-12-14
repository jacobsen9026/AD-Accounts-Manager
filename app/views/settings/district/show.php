<?php
/* @var $district District */

use app\database\Schema;

echo $this->modal('deleteDistrict');
$grades = array('PK3', 'PK4', 'K', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
?>


<div class = "container">
    <div class = "mb-3">
        <strong>
            <?php
            echo $this->district[Schema::DISTRICT_NAME];
            //var_dump($this->district);
            ?>
        </strong>
        <button type="button" class="close text-danger" aria-label="Close" data-toggle="modal" data-target="#deleteDistrictModal">
            <span aria-hidden="true">&times;</span>
        </button>

    </div>

    <form method="post" class ="table-hover" action="/districts/edit">
        <div class="">
            <label class="mr-2" for="abbreviation">Abbreviation</label><input name="abbreviation" type="text" value="<?php echo $this->district[Schema::DISTRICT_ABBREVIATION]; ?>"/>
            <div class="mb-3">Grade Span<select class="ml-2" name="gradeSpanFrom">

                    <?php
                    foreach (GRADE_CODES as $grade) {
                        ?>
                        <option <?php
                        if ($this->district[Schema::DISTRICT_GRADESPANFROM] == $grade) {
                            echo "selected";
                        }
                        ?> value ="<?php echo $grade; ?>"><?php echo $grade; ?></option>
                            <?php
                        }
                        ?>

                </select>-<select name="gradeSpanTo">

                    <?php
                    foreach (GRADE_CODES as $grade) {
                        ?>
                        <option <?php
                        if ($this->district[Schema::DISTRICT_GRADESPANTO] == $grade) {
                            echo "selected";
                        }
                        ?> value ="<?php echo $grade; ?>"><?php echo $grade; ?></option>
                            <?php
                        }
                        ?>
                </select>
            </div>
        </div>
        <?php
        echo $this->view('settings/district/nav');
        ?>
        <div class="">
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
        <input hidden type="hidden" name="districtID" value="<?php echo $this->district[Schema::DISTRICT_ID]; ?>"/>

    </form>
</div>


