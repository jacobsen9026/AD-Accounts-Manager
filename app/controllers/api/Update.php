<?php


namespace app\controllers\api;

use App\App\AppUpdater;
use App\Controllers\Settings\Update as UpdateController;


class Update extends APIController
{
    public function updateApp()
    {
        $controller = new UpdateController($this->app);
        $this->updater = new AppUpdater();
        return $this->returnHTML($controller->indexPost());
        //return $this->returnHTML($this->updater->update(false));
    }
}