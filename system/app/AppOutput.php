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
 * Description of AppOutput
 *
 * @author cjacobsen
 */

use System\Log\CommonLogger;
use System\Request;
use System\type;

class AppOutput
{

//put your code here
    private $body;
    private $ajax = [];


    /**
     *
     * @deprecated since version number
     */
    private $logs;

    /**
     *  All loggers used by the application
     *  that should be returned to the core for rendering
     *
     * @var array <CommonLoggers>
     */
    private $loggers = [];


    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        // var_dump($this->app->request->getType());
        if (Request::get()->getType() == 'ajax') {
            $this->addAjax($body);
        } elseif (Request::get()->getType() == 'http') {
            //var_dump($body);
            $this->body = $body;
        }
    }

    public function addAjax($array)
    {
        //var_dump($array);
        //var_dump(backTrace());
        if (!empty($this->ajax)) {
            $this->ajax = array_merge($this->ajax, $array);
        } else {
            $this->ajax = $array;
        }
        //var_dump($this->ajax);
        //var_dump($this->body);
        return $this;
    }

    public function getAjax()
    {
        return $this->ajax;
    }

    public function setAjax($ajax)
    {
        $this->ajax = $ajax;
        return $this;
    }

    public function appendBody($body)
    {
        if (Request::get()->getType() == 'ajax') {
            $this->addAjax($body);
        } elseif (Request::get()->getType() == 'http') {
            //var_dump($body);
            $this->body .= $body;
        }
    }

    public function prependBody($body)
    {
        $this->logger->debug($body);
        if ($this->request->getType() == 'ajax') {
            $this->addAjax($body);
        } elseif ($this->request->getType() == 'http') {
            //var_dump($body);
            $this->body = $body . $this->body;
        }
    }

    /**
     *
     * @return array
     */
    public function getLoggers(): array
    {
        return $this->loggers;
    }

    /**
     *
     * @param CommonLogger $logger
     *
     * @return $this
     */
    public function addLogger(CommonLogger $logger)
    {
        $this->loggers[] = $logger;
        return $this;
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $var) {
            if (is_object($var)) {
                $var = get_object_vars($var);
            }
            $varsR[] = $var;
        }
        return $vars;
    }

}
