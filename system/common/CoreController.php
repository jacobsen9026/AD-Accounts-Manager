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

namespace system\common;

/**
 * Description of Controller
 *
 * @author cjacobsen
 */
use system\Parser;
use app\App;
use system\CoreApp;
use app\config\MasterConfig;

class CoreController extends Parser {

    /** @var App|null The view parser */
    public $app;

    /** @var MasterConfig|null The view parser */
    public $config;

    /** @var string|null The view parser */
    public $layout;
    public $postSet = false;
    public $getSet = false;

    //put your code here
    function __construct($app) {
        $this->app = $app;
        $this->config = $app->config;


        if (isset($_POST) and $_POST != null) {

            $this->postSet = true;
        }

        if (isset($_GET) and $_GET != null) {
            $this->getSet = true;
        }
    }

    public function unauthorized() {
        return $this->view('errors/403');
    }

}

?>
