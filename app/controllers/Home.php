<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jacobsen\app\Controllers;

/**
 * Description of Home
 *
 * @author cjacobsen
 */
class Home extends BaseController{
    //put your code here
    public function index(){
        echo "home";
         $this->app->addToBody("test");
    }
}
