<?php


namespace app\controllers;


use App\App\AppUpdater;
use System\Post;

class Update extends Controller
{

    public function indexPost()
    {
        $csrfCheck = Post::get();
        $updater = new AppUpdater();
        return $updater->update(false);


    }
}