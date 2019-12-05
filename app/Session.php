<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app;

/**
 * Description of Session
 *
 * @author cjacobsen
 */
use system\CoreSession;

class Session extends CoreSession {

    //put your code here
    public $user;
    public $authenticated;
    private $config;

}
