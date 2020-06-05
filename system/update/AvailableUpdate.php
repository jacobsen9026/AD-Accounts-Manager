<?php


namespace system\update;


class AvailableUpdate
{
    public $version;
    public $downloadURL;
    public $postInstallScriptPath;
    public $fileCount;

    public function passesChecks()
    {

        if (!is_null($this->version) && !is_null($this->downloadURL)) {
            return true;
        } else {
            return false;
        }
    }


}