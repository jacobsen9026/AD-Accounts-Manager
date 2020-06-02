<?php


namespace app\controllers;


use App\App\AppUpdater;
use System\App\AppException;
use System\Post;

class Update extends Controller
{

    public function indexPost()
    {
        $updater = new AppUpdater();
        $updateToken = $updater->getUpdateToken();
        if ($updateToken === Post::get('updateToken')) {
            return $updater->update();
        } else {
            throw new AppException('Update tokens did not match');
        }

    }
}