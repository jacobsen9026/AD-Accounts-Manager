<?php

namespace App\Views;
class Homepage
{


    public function getDabaseConnection()
    {
        //echo "<br/><br/><br/><br/><br/><br/><br/>test";
        //echo $this->view('install/index');
        echo $this->applicationName;
        //echo $appConfig["webAppName"];

        echo $this->motd;
    }

}