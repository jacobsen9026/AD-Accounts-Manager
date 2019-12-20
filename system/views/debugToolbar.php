<?php
if ((defined('DEBUG_MODE') and boolval(DEBUG_MODE) and ($this->core->logger != null))or isset($this->appLogger)) {



    echo "<style>";
    include(ROOTPATH . DIRECTORY_SEPARATOR . "system" . DIRECTORY_SEPARATOR . "system.css");
    echo "</style>";
    //var_dump($this->appLogger->getLogs());
    //var_dump($this->logger->getLogs());
    ?>
    <div class='content'>

        <button type="button" id = "showDebugButton" class="mx-auto btn btn-primary dark-shadow <?php
        if ($this->errors_exists()) {
            echo 'btn-danger';
        }
        ?> " data-toggle="modal" data-target="#logsModal">Logs</button>

        <div id = "logsModal" class = "modal fade" role = "dialog">
            <div class = "modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">

                <!--Modal content-->
                <div class = "modal-content">
                    <div class = "modal-header border-0 bg-primary text-light">
                        <h4 class = "modal-title">Logs</h4>
                        <button type = "button" class = "close text-light" data-dismiss = "modal">&times;
                        </button>

                    </div>
                    <div class = "modal-body p-0">


                        <!--Nav tabs-->
                        <ul class = "sticky-top nav nav-pills bg-primary row">
                            <?php
                            if ($this->core->inDebugMode()) {
                                ?>

                                <li class = "nav-item col">
                                    <a class = "nav-link btn-primary active rounded-0 text-center " data-toggle = "pill" href = "#home">System</a>
                                </li>
                                <?php
                            }if (!empty($this->core->app) and $this->core->app->inDebugMode()) {
                                ?>
                                <li class = "nav-item col">
                                    <a class = "nav-link btn-primary rounded-0 text-center" data-toggle = "pill" href = "#menu1">App</a>
                                </li>
                                <?php
                            }
                            ?>
                            <li class = "nav-item col">
                                <a class = "nav-link btn-primary rounded-0 text-center" data-toggle = "pill" href = "#menu2">Session</a>
                            </li>


                        </ul>

                        <!--Tab panes-->
                        <div class = "pt-0 tab-content log-tab-content">
                            <?php
                            if ((defined('DEBUG_MODE') and boolval(DEBUG_MODE) and ($this->core->logger != null))) {
                                ?>
                                <div class = "tab-pane   container-fluid active p-0" id = "home">
                                    <?php $this->include('system/views/debugToolbar/system_debug'); ?>
                                </div>
                                <?php
                            }
                            if (isset($this->appLogger)) {
                                ?>
                                <div class = "tab-pane  container-fluid fade p-0" id = "menu1">
                                    <?php
                                    $this->include('system/views/debugToolbar/app_debug');
                                    ?>
                                </div>

                                <?php
                            }
                            if (isset($_SESSION)) {
                                ?>
                                <div class = "tab-pane  container-fluid fade p-0" id = "menu2">
                                    <?php
                                    $this->include('system/views/debugToolbar/session_debug');
                                    ?>
                                </div>

                                <?php
                            }
                            ?>

                        </div>
                        <br/><br/>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php
}
?>