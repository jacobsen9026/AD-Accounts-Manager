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

namespace System;

/**
 * Description of Request
 *
 * @author cjacobsen
 */
class Request {

    /**
     *
     * @var string
     */
    public $uri;
    public $referer;
    public $type;
    public $serverName;
    public $protocol = "http";
    private $logger;
    private $id;

    /**
     *
     * @param \SAM\App $core
     */
    public function __construct() {
        $this->logger = SystemLogger::get();
        $this->id = substr(hash("sha256", rand()), 0, 5);
        /*
         * Store the referer
         */
        if (isset($_SERVER['HTTP_REFERER'])) {
            $this->setReferer($_SERVER['HTTP_REFERER']);
        }
        /*
         * Store the webserver name used
         */
        if (isset($_SERVER['SERVER_NAME'])) {
            $this->setServerName($_SERVER['SERVER_NAME']);
        }
        /*
         * Check that URI is set
         */
        if (isset($_SERVER["REQUEST_URI"])) {
            /*
             * Set Request URI
             */
            $this->setUri($_SERVER["REQUEST_URI"]);
            if (Get::isSet()) {
                //Remove Get portion from URI
                $this->logger->info("URI: " . $this->getUri());
                $this->logger->info("Position of ? " . strpos($this->getUri(), "?"));
                if (strpos($this->getUri(), "?")) {
                    $this->setUri(explode("?", $this->getUri())[0]);
                } elseif ($this->getUri()[0] == "?") {
                    $this->setUri("");
                }
            }
        }
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->setType('ajax');
        } else {
            $this->setType('http');
        }
        if (isset($_SERVER["HTTPS"])) {
            $this->setProtocol("https");
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getUri(): string {
        return $this->uri;
    }

    public function getReferer() {
        return $this->referer;
    }

    public function getType() {
        return $this->type;
    }

    public function getServerName() {
        return $this->serverName;
    }

    public function getProtocol() {
        return $this->protocol;
    }

    public function setUri(string $uri) {
        $this->uri = $uri;
        return $this;
    }

    public function setReferer($referer) {
        $this->referer = $referer;
        return $this;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setServerName($serverName) {
        $this->serverName = $serverName;
        return $this;
    }

    public function setProtocol($protocol) {
        $this->protocol = $protocol;
        return $this;
    }

}
