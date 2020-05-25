<?php


namespace App\Controllers;


use System\Get;

class Util extends Controller
{

    public function redirectGet()
    {

        $this->redirect(Get::get("url"));
    }


}