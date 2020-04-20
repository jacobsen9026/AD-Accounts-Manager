<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app;

/**
 * Description of Route
 *
 * @author cjacobsen
 */
use system\app\Router;

class Route {

    //put your code here
    private $controler;
    private $method;
    private $data;

    public function getControler() {
        return $this->controler;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getData() {
        return $this->data;
    }

    public function setControler($controler) {
        $this->controler = $controler;
        return $this;
    }

    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }

    public function setData($data) {
        $this->data = $data;
        return $this;
    }

    /**
     *
     * Shift method to controller, and split data by /'s and set first segment
     * as method. Finally break trim the first segment from the data.
     *
     * @param Route $this
     * @return Route Description
     */
    public function unfoldLeft() {
        $this->setControler($this->getMethod());
        $this->setMethod(explode("/", $this->getData())[0]);

        if (is_null($this->getMethod()) or $this->getMethod() == '') {
            $this->setMethod(Router::getDefaultMethod());
        }
        if (sizeof(explode("/", $this->getData(), 2)) > 1) {
            $this->setData(explode("/", $this->getData(), 2)[1]);
        } else {
            $this->setData(null);
        }
        return $this;
    }

}
