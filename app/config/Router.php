<?php

/*
 * The MIT License
 *
 * Copyright 2019 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace app\config;

/**
 * Description of Router
 *
 * @author cjacobsen
 */
use system\common\CommonRouter;
use system\app\App;

class Router extends CommonRouter {
    /*
     * Add custom routes with the following sytax
     * array ("requestedModule"
     */

    //put your code here
    function __construct(App $app) {
        parent::__construct($app);
        $this->includeCustomRoutes();
    }

    private function includeCustomRoutes() {
        $this->customRoutes[] = array("install", "index", "home", "index");

        /*
         * Settings routes
         */
        $this->customRoutes[] = array("Districts", "*", "settings\Districts", "*");
        $this->customRoutes[] = array("Schools", "*", "settings\Schools", "*");
        $this->customRoutes[] = array("Grades", "*", "settings\Grades", "*");
        $this->customRoutes[] = array("Departments", "*", "settings\Departments", "*");
        $this->customRoutes[] = array("Teams", "*", "settings\Teams", "*");
        //$this->customRoutes[] = array("Draw", "*", "api\Draw", "*");
        //$this->customRoutes[] = array("Ldap", "*", "api\LDAP", "*");
        //var_dump($this->customRoutes);
    }

}

?>
