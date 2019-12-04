<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system;

/**
 * Description of CoreLayout
 *
 * @author cjacobsen
 */
use app\App;

class CoreLayout extends Parser {

    public $layoutName;
    public $app;
    private $appOutput;

//put your code here
    function __construct(App $app) {
        $this->app = $app;
        if (isset($app->controller)) {
            if (isset($app->controller->layout)) {
                $this->layoutName = $app->controller->layout;
            } else {
                $this->layoutName = 'default';
            }
        }
//$this->app->debug($app->outputBody);
    }

    public function apply() {
        $this->appOutput = $this->getHeader() . $this->getNavigation() . $this->app->outputBody . $this->getFooter();
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
        return $this->view('layouts/' . $this->layoutName . '_navigation');
    }

    private function renderDebug($log) {
        $this->appOutput .= $this->view('layouts/app_debug');
    }

}

?>
