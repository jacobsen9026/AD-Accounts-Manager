<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\Controllers;

/**
 * Description of Home
 *
 * @author cjacobsen
 */
class Home extends Controller {

    //put your code here
    public function index() {
        $this->layoutName = "default";
        //echo "test";
        return $this->view('homepage');
        //var_dump($this->content);
        //$this->app->addToBody("test");
    }

}

?>
