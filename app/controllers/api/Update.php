<?php


namespace app\controllers\api;

use App\App\AppUpdater;


class Update extends APIController
{
    public function updateApp()
    {
        $this->updater = new AppUpdater();
        return $this->returnHTML($this->updater->update());
    }
}