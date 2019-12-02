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

class CoreLayout {

    public $layoutName;
    private $app;

    //put your code here
    function __construct(App $app) {
        $this->app = $app;
        if (isset($app->controller)) {
            if (isset($app->controller->layoutName)) {
                $this->layoutName = $app->controller->layoutName;
            } else {
                $this->layoutName = 'default';
            }
        }
        //$this->app->debug($app->outputBody);
    }

    public function apply() {
        //var_export($this->app->debugLog);
        $output = $this->getHeader() . $this->app->outputBody . $this->getFooter();
        if (isset($this->app->debugLog) and sizeof($this->app->debugLog) > 0) {
            //$output .= '<br/><br/><br/>Application Debug:<br/>';
            //$output .= $this->renderDebug($this->app->debugLog);
        }
        return $output;
    }

    public function getHeader() {
        return file_get_contents(ROOTPATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $this->layoutName . '_header.php');
    }

    public function getFooter() {
        return file_get_contents(ROOTPATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $this->layoutName . '_footer.php');
    }

    private function renderDebug($log) {
        $output = null;
        $debugLayoutFile = ROOTPATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'app_debug.php';
        foreach ($log as $entry) {
            $output .= $entry . "<br/>";
        }
        //var_export(file_get_contents($debugLayoutFile));
        if (file_exists($debugLayoutFile)) {
            //var_export(file_get_contents($debugLayoutFile));
            return $output;
        }
    }

}

?>
