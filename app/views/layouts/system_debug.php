<style>
<?php include(ROOTPATH . DIRECTORY_SEPARATOR . "system" . DIRECTORY_SEPARATOR . "system.css"); ?>
</style>
<div class="systemDebugToolBar">
    <div class="systemDebugContainer">
        <div>System Error:</div>
        <div><?= $this->bufferVarDump($this->core->logger->getLogs()['error']); ?></div>
    </div>
    <div class="systemDebugContainer">
        <div>System Warning:</div>
        <div><?= $this->bufferVarDump($this->core->logger->getLogs()['warning']); ?></div>
    </div>

    <div class="systemDebugContainer">
        <div>System Debug:</div>
        <div><?= $this->bufferVarDump($this->core->logger->getLogs()['debug']); ?></div>
    </div>
    <div class="systemDebugContainer">
        <div>System Info:</div>
        <div><?= $this->bufferVarDump($this->core->logger->getLogs()['info']); ?></div>
    </div>
</div>

