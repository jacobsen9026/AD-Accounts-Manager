<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\api;

/**
 * Description of APIController
 *
 * @author cjacobsen
 */
use app\controllers\Controller;

class APIController extends Controller {

    //put your code here
    public function __construct(\system\app\App $app) {
        parent::__construct($app);
        $this->app->request->type = 'ajax';
        $this->app->core->request->type = 'ajax';
    }

}
