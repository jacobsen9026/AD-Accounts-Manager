<nav class="navbar navbar-expand-md bg-primary navbar-dark">
    <!-- Brand -->
    <a class="navbar-brand" href="/">School Accounts Manager</a>

    <!-- Toggler/collapsibe Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav  align-right">
            <!-- Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    <img style="height:2em;" class="mr-1" src="/img/user_avatar.png"/>Username
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Profile</a>
                    <a class="dropdown-item" href="#">Settings</a>
                    <?php if ($this->app->inDebugMode()) {
                        ?>
                        <a class="dropdown-item" href="#"><text data-toggle="modal" data-target="#debugConfigModal">View Config</text></a>
                        <?php
                    }
                    ?>
                    <a class="dropdown-item" href="#">Logout</a>
                </div>
            </li>
        </ul>

        <ul class="navbar-nav">
            <!-- Student Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    Students
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Account Status</a>
                    <a class="dropdown-item" href="#">Account Change</a>
                    <a class="dropdown-item" href="#">Google Classroom</a>
                    <a class="dropdown-item" href="#">Google Groups</a>
                    <a class="dropdown-item" href="#">H-Drive</a>
                    <a class="dropdown-item" href="#">New Password</a>
                    <a class="dropdown-item" href="#">Create Account</a>
                </div>
            </li>
            <!-- Staff Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    Staff
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Account Status</a>
                    <a class="dropdown-item" href="#">Account Change</a>
                    <a class="dropdown-item" href="#">Google Groups</a>
                    <a class="dropdown-item" href="#">New Password</a>
                    <a class="dropdown-item" href="#">Create Account</a>
                    <a class="dropdown-item" href="#">Send Welcome Email</a>
                </div>
            </li>
            <!-- Parent Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    Parents
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Google Group Check</a>
                    <a class="dropdown-item" href="#">Google Groups Manager</a>
                </div>
            </li>
            <!-- Tech Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    Tech
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Google</a>
                    <a class="dropdown-item" href="#">Google Drive</a>
                    <a class="dropdown-item" href="#">Computers</a>

                </div>
            </li>
        </ul>

    </div>
</nav>
<?php
if ($this->app->inDebugMode()) {
    echo $this->view('modals/debugConfigModal');
}
?>







