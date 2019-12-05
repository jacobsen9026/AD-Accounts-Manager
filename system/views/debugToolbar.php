<?php
if ((defined('DEBUG_MODE') and boolval(DEBUG_MODE) and ($this->core->logger != null))or isset($this->appLogger)) {
    ?>

    <div class = 'systemDebugToolBar'>
        <div class='content'>
            <button class="btn btn-info rounded-0 " id = "showDebugButton" onclick = "document.getElementsByClassName('systemDebugToolBar')[0].classList.toggle('shown');">Logs</button>

            <?php
            echo "<style>";
            include(ROOTPATH . DIRECTORY_SEPARATOR . "system" . DIRECTORY_SEPARATOR . "system.css");
            echo "</style>";
            ?>
            <!--Nav tabs-->
            <ul class = "nav nav-pills bg-info row">
                <li class = "nav-item col">
                    <a class = "nav-link btn-info active rounded-0 text-center " data-toggle = "pill" href = "#home">System</a>
                </li>
                <li class = "nav-item col">
                    <a class = "nav-link btn-info rounded-0 text-center" data-toggle = "pill" href = "#menu1">App</a>
                </li>
            </ul>

            <!--Tab panes-->
            <div class = "tab-content  bg-white log-tab-content">
                <?php
                if ((defined('DEBUG_MODE') and boolval(DEBUG_MODE) and ($this->core->logger != null))) {
                    ?>
                    <div class = "tab-pane container-fluid active p-0" id = "home">
                        <?php $this->include('system/views/system_debug'); ?>
                    </div>
                    <?php
                }
                if (isset($this->appLogger)) {
                    ?>
                    <div class = "tab-pane container-fluid fade p-0" id = "menu1">
                        <?php
                        $this->include('system/views/app_debug');
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}
?>