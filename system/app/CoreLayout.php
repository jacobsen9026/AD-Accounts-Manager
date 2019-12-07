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

namespace system\app;

/**
 * Description of CoreLayout
 *
 * @author cjacobsen
 */
use app\App;
use system\Parser;

class CoreLayout extends Parser {

    const DEFAULT_LAYOUT_NAME = 'default';

    /** @var string|null The view parser */
    public $layoutName;

    /** @var App|null The view parser */
    public $app;

    /** @var string|null The view parser */
    private $appOutput;

//put your code here
    function __construct($app) {
        $this->app = $app;
        if (isset($app->controller)) {
            if (isset($app->controller->layout)) {
                $this->layoutName = $app->controller->layout;
            } else {
                $this->layoutName = $this::DEFAULT_LAYOUT_NAME;
            }
        }
//$this->app->debug($app->outputBody);
    }

    public function apply() {
        $this->appOutput = $this->getNavigation() . $this->getHeader() . $this->app->outputBody . $this->getFooter();
        if ($this->app->logger != null) {
            // $this->appOutput .= $this->renderDebug($this->app->debugLog);
        }
        //var_dump($this->appOutput);
        return $this->appOutput;
    }

    public function getHeader() {
        return $this->view('layouts/' . $this->layoutName . '_header');
    }

    public function getFooter() {
        return $this->view('layouts/' . $this->layoutName . '_footer');
    }

    public function getNavigation() {
        $menu = new \app\controllers\Menu(\app\models\user\Privilege::TECH);
        return $menu->getMenu();
        return $this->view('layouts/' . $this->layoutName . '_navigation');
    }

    private function renderDebug($log) {
        $this->appOutput .= $this->view('layouts/app_debug');
    }

}

?>
