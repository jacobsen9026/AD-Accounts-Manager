<?php


namespace App\Controllers\Settings;


class Backup extends SettingsController
{
    public function index()
    {

        $this->tab = 'backup';
        return $this->view('settings/index');
    }

}