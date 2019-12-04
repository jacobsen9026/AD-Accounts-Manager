<?php
if ((defined('DEBUG_MODE') and boolval(DEBUG_MODE) and ($this->core->logger != null))or isset($this->appLogger)) {
    ?>

    <div class = 'systemDebugToolBar'>
        <button id = "showDebugButton" onclick = "document.getElementsByClassName('systemDebugToolBar')[0].classList.toggle('shown');">Show Debug</button>

        <?php if ($this->core->inDebugMode()) {
            ?>
            <button id = "systemDebugButton" onclick = "document.getElementsByClassName('systemTab')[0].classList.toggle('shown');this.classList.toggle('shown');">System</button>

            <?php
        }
        ?>

        <button id = "appDebugButton" onclick = "document.getElementsByClassName('appTab')[0].classList.toggle('shown');this.classList.toggle('shown');">App</button>
        <?php
        echo "<style>";
        include(ROOTPATH . DIRECTORY_SEPARATOR . "system" . DIRECTORY_SEPARATOR . "system.css");
        echo "</style>";
        echo '<div class ="tabContainer">';
        if (defined('DEBUG_MODE') and boolval(DEBUG_MODE) and ($this->core->logger != null)) {
            echo $this->view('layouts/system_debug');
        }
        if (isset($this->appLogger)) {
            echo $this->view('layouts/app_debug');
        }
        ?>
    </div>
    </div>
    <?php
}
?>