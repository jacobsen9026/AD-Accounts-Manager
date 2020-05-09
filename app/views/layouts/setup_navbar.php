<?php

use app\database\Schema;
?>
<script>
    $(function () {
        $(".navbar-toggler").on("click", function () {
            $('.navbar-toggler i').toggleClass("fa-chevron-down fa-chevron-up");
        });
    });
</script>


<nav class="navbar navbar-expand-md navbar-dark sticky-top district-nav bg-success shadow-sm w-100">
    <!-- Brand -->
    <a class="nav-link text-weight-bold text-light" href="/settings/district">District Setup</a>
    <?php
    if (isset($this->schoolID)) {
        ?>

        <!-- Toggler/collapsibe Button -->
        <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#collapsibleNavbar2">
            <i class="fas fa-chevron-down"></i>

        </button>


        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="collapsibleNavbar2">






            <ul class="navbar-nav">
                <?php
                $breadCrumb = '
                    <li class="d-none d-md-block nav-item">
                        <a class="nav-link">
                            >
                        </a>
                    </li>';
                if (isset($this->schoolID)) {

                    echo $breadCrumb;
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/settings/schools/show/<?php echo $this->districtID; ?>">Schools</a>
                    </li>
                    <?php
                }
                if (isset($this->schoolID) and isset($this->controller) and $this->controller != 'Schools') {
                    ?>
                    <li class="d-none d-md-block nav-item">
                        <a class="nav-link">
                            >
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/settings/schools/edit/<?php echo $this->schoolID; ?>"><?= $this->schoolName; ?></a>
                    </li>
                    <?php
                }
                if (isset($this->gradeID)) {

                    echo $breadCrumb;
                    ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/settings/grades/show/<?php echo $this->schoolID; ?>">Grades</a>
                    </li>
                    <?php
                }
                if (isset($this->teams) and isset($this->controller) and $this->controller != 'Grades') {

                    echo $breadCrumb;
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/settings/grades/edit/<?php echo $this->gradeID; ?>">Grade <?= $this->grade[Schema::GRADEDEFINITION_DISPLAY_CODE[app\database\Schema::COLUMN]]; ?></a>
                    </li>
                    <?php
                }
                if (isset($this->teamID)) {
                    echo $breadCrumb;
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/settings/teams/show/<?php echo $this->gradeID; ?>">Teams</a>
                    </li>
                    <?php
                }
                ?>


            </ul>

        </div>

        <?php
    }
    ?>
</nav>



