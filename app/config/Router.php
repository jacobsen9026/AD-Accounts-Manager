<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jacobsen\app\config;

/**
 * Description of Router
 *
 * @author cjacobsen
 */
use jacobsen\system\CoreRouter;

class Router extends CoreRouter {

    //put your code here
    function __construct(\jacobsen\app\App $app) {
        parent::__construct($app);
    }

}

?>
