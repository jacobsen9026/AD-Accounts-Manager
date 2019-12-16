<nav class="navbar fixed-top navbar-expand-md bg-primary navbar-dark">
    <!-- Brand -->
    <a class="navbar-brand" href="/"><?php echo $this->config->app->getName(); ?></a>

    <?php

    use system\app\App;
    use app\models\user\Privilege;

if ($this->userPrivs > Privilege::UNAUTHENTICATED) {
        ?>
        <!-- Toggler/collapsibe Button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
    <?php } ?>

    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">






        <ul class="navbar-nav">

            <?php
            //var_dump($this);
            if (isset($this->items) and $this->items != null) {
                foreach ($this->items as $topItem) {
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                            <?php echo $topItem->displayText; ?>
                        </a>
                        <div class="dropdown-menu">


                            <?php
                            foreach ($topItem->subItems as $subItem) {
                                ?>
                                <a class="dropdown-item" href="<?php echo $subItem->targetURL; ?>"><?php echo $subItem->displayText; ?></a>

                                <?php
                            }
                            ?>

                        </div>
                    </li>



                    <?php
                }
            }
            ?>
        </ul>
        <?php if ($this->userPrivs > Privilege::UNAUTHENTICATED) {
            ?>
            <div class="d-md-flex text-center flex-md-row-reverse w-100">
                <?php if ($this->userPrivs >= Privilege::TECH) {
                    ?>
                    <ul class="order-md-1 navbar-nav">
                        <!-- Settings Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                <i class="fas fa-tools"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right pt-0">
                                <div class="dropdown-header bg-light">Settings</div>



                                <a class="dropdown-item" href="/settings">Application</a>
                                <a class="dropdown-item" href="/districts">District Setup</a>

                                <?php if (App::get()->inDebugMode()) {
                                    ?>
                                    <a class="dropdown-item" href="#"><text data-toggle="modal" data-target="#debugConfigModal">View Config</text></a>
                                    <?php
                                }
                                ?>
                            </div>
                        </li>
                    </ul>
                    <?php
                }
                ?>
                <ul class="order-md-0 navbar-nav">
                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>

                        </a>
                        <div class="dropdown-menu dropdown-menu-right pt-0">
                            <div class="dropdown-header bg-light"> <?php echo $this->user->username; ?></div>

                            <a class="dropdown-item" href="/profile">Profile</a>

                            <a class="dropdown-item" href="/logout">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
            <?php
        }
        ?>

    </div>
</nav>
<?php
if (App::get()->inDebugMode() and $this->userPrivs == Privilege::TECH) {
    echo $this->view('modals/debugConfigModal');
}
?>







