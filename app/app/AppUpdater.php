<?php


namespace App\App;


use System\Updater;

class AppUpdater extends Updater
{
    public function __construct()
    {
        $tmp = ROOTPATH . DIRECTORY_SEPARATOR . "writable" . DIRECTORY_SEPARATOR . "core_update";
        $dst = SYSTEMPATH;
        parent::__construct('https://raw.githubusercontent.com/jacobsen9026/Classify/master/update', $tmp, $dst, App::$version);
        $this->setCheckSSL(false)
            ->setLastCheckedFile(WRITEPATH . DIRECTORY_SEPARATOR . 'lastUpdateCheck.log');
    }


}