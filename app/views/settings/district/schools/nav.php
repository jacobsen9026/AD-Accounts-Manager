
<script>
    $(function () {
        $(".navbar-toggler").on("click", function () {
            $('.navbar-toggler i').toggleClass("fa-chevron-down fa-chevron-up");
        });
    });
</script>


<nav class="navbar navbar-expand-md navbar-dark sticky-top district-nav bg-success shadow-sm w-100">
    <!-- Brand -->
    <a class="nav-link text-weight-bold text-light" href="/districts">District Setup</a>
    <?php
    if (!empty($this->district) and isset($this->controller) and $this->controller != 'Districts') {
        ?>

        <!-- Toggler/collapsibe Button -->
        <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#collapsibleNavbar2">
            <i class="fas fa-chevron-down"></i>

        </button>


        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="collapsibleNavbar2">






            <ul class="navbar-nav">
                <?php
                if (isset($this->schoolID)) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/schools/show/<?php echo $this->districtID; ?>">Schools</a>
                    </li>
                    <?php
                }
                if (isset($this->grades) and isset($this->controller) and $this->controller != 'Schools') {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/schools/edit/<?php echo $this->schoolID; ?>"><?= $this->schoolName; ?></a>
                    </li>
                    <?php
                }
                if (isset($this->gradeID)) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/grades/show/<?php echo $this->schoolID; ?>">Grades</a>
                    </li>
                    <?php
                }
                if (isset($this->teams) and isset($this->controller) and $this->controller != 'Grades') {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/grades/edit/<?php echo $this->gradeID; ?>">Grade <?= $this->grade[\app\database\Schema::GRADES_VALUE]; ?></a>
                    </li>
                    <?php
                }
                if (isset($this->teamID)) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/teams/show/<?php echo $this->gradeID; ?>">Teams</a>
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



