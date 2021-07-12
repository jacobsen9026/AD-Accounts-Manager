<?php

/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
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

namespace System\App;

/**
 * Description of Route
 *
 * @author cjacobsen
 */

use System\App\Router;

class Route
{

    /**
     *
     * @var string The controller decided by the router
     */
    private $controler;

    /**
     *
     * @var string The method decided by the router\
     */
    private $method;

    /**
     *
     * @var string The data if supplied and base on routing
     */
    private $data;

    /**
     *
     * Shift method to controller, and split data by /'s and set first segment
     * as method. Finally break trim the first segment from the data.
     *
     * @param Route $this
     *
     * @return Route Description
     */
    public function unfoldLeft()
    {
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

    /**
     *
     * @return type
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     *
     * @param type $method
     *
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $this->preProcessMethod($method);
        return $this;
    }

    /**
     *
     * @return type
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     *
     * @param type $data
     *
     * @return $this
     */
    public function setData($data)
    {

        $this->data = $this->preProcessData($data);
        return $this;
    }

    public function getString()
    {
        return $this->getControler() . "->" . $this->getMethod() . "->" . $this->getData();
    }

    /**
     *
     * @return type
     */
    public function getControler()
    {
        return $this->controler;
    }

    /**
     *
     * @param type $controler
     *
     * @return $this
     */
    public function setControler($controler)
    {
        $this->controler = $this->preProcessController($controler);
        return $this;
    }

    /**
     * Converts HTML encoded characters to string
     *
     * @param string $data
     *
     * @return string
     */
    private function preProcessData($data)
    {
        return urldecode($data);
        //return str_replace("%20", " ", $data);

    }

    private function preProcessController($controller)
    {
        return str_replace(".", "_", $controller);
    }

    private function preProcessMethod($method)
    {
        return str_replace(".", "_", $method);
    }

}
