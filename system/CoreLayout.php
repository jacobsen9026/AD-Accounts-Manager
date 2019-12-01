<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jacobsen\system;

/**
 * Description of CoreLayout
 *
 * @author cjacobsen
 */
use jacobsen\app\App;

class CoreLayout {

    public $layoutName;

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
    }

    public function apply($body) {
        $output = $this->getHeader() . $body . $this->getFooter();
        if (isset($this->app->debugLog) and sizeof($this->app->debugLog) > 0) {
            $this->renderDebug();
        }
    }

    public function getHeader() {
        return file_get_contents(ROOTPATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $this->layoutName . '_header.php');
    }

    public function getFooter() {
        return file_get_contents(ROOTPATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $this->layoutName . '_footer.php');
    }

    private function renderDebug() {
        foreach ($this->app->debugLog as $entry) {
            $this->debugOutput .= $entry . "<br/>";
        }
        if (file_exists(ROOTPATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'app_debug.php')) {
            
        }
    }

}

?>
