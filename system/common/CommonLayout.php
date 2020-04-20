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
 * Description of CoreLayout
 *
 * @author cjacobsen
 */
use system\app\App;
use system\Parser;
use app\models\user\User;
use app\controllers\Menu;

class CommonLayout extends Parser {

    const DEFAULT_LAYOUT_NAME = 'default';

    /** @var string|null The view parser */
    public $layoutName;

    /** @var App|null The view parser */
    public $app;

    /** @var User|null The view parser */
    public $user;

    /** @var string|null The view parser */
    private $layoutOutput;

//put your code here
    function __construct($app) {
        $this->app = $app;
        $this->user = $app->user;
        if (isset($app->controller) and $app->controller != null) {
            if (isset($app->controller->layout)) {
                $this->layoutName = $app->controller->layout;
            } else {
                $this->layoutName = $this::DEFAULT_LAYOUT_NAME;
            }
        } else {
            $this->layoutName = $this::DEFAULT_LAYOUT_NAME;
        }
//$this->app->debug($app->outputBody);
    }

    public function apply() {
        //var_dump($this->app->request->type);
        if ($this->app->request->type == 'http') {
            $this->layoutOutput = $this->getHeader();
        }
        $this->layoutOutput .= $this->app->appOutput->getBody();

        if ($this->app->request->type == 'http') {
            $this->layoutOutput .= $this->getNavigation();
            $this->layoutOutput .= $this->getFooter();
        }

        //var_dump($this->appOutput);
        return $this->layoutOutput;
    }

    public function getHeader() {
        $this->app->logger->debug('getting header');
        return $this->view('layouts/' . $this->layoutName . '_header');
    }

    public function getFooter() {
        return $this->view('layouts/' . $this->layoutName . '_footer');
    }

    public function getNavigation() {
        // var_dump($this->user);
        $menu = new Menu($this->user);
        return $menu->getMenu();
    }

}

?>
