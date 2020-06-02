<?php


namespace App\App;


use System\App\AppLogger;
use System\Updater;

class AppUpdater extends Updater
{
    public function __construct()
    {
        $this->user = App::get()->user;
        $tmp = ROOTPATH . DIRECTORY_SEPARATOR . "writable" . DIRECTORY_SEPARATOR . "core_update";
        $dst = ROOTPATH;

        parent::__construct('https://raw.githubusercontent.com/jacobsen9026/AD-Accounts-Manager/master/update', $tmp, $dst, App::$version);
        $this->logger->info("Creating updater");
        $this->setCheckSSL(false);
    }

    public function getUpdateToken()
    {

        $updateToken = hash("sha256", $this->user->getUsername() . $this->getLatestVersion());
    }

    public function getSimulationResults()
    {
        return $this->updater->getSimulationResults();
    }


}