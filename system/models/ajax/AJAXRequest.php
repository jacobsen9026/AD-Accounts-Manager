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

namespace System\Models\Ajax;

/**
 * Description of AJAXRequest
 *
 * @author cjacobsen
 */

use System\App\Forms\FormElement;
use System\App\Forms\Form;
use App\Models\View\Javascript;

class AJAXRequest
{

    private $targetURL;
    private $outputID;

    /**
     *
     * @var type
     */
    private $outputElement;

    /**
     *
     * @var string String version of what should be the data.
     * Either a json array or a function call that would provide such.
     */
    private $data;

    /**
     *
     * @var string The HTML content of what should be displayed while waiting for a response
     */
    private $loadingHTML;

    public function __construct($targetURL, $data)
    {
        $data = $this->preprocessData($data);
        $this->setTargetURL($targetURL)
            ->setData($data);
    }

    public function getTargetURL()
    {
        return $this->targetURL;
    }

    public function getOutputID()
    {
        return $this->outputID;
    }

    public function getOutputField()
    {
        return $this->outputElement;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getLoadingHTML()
    {
        return $this->loadingHTML;
    }

    public function setTargetURL($targetURL)
    {
        $this->targetURL = $targetURL;
        return $this;
    }

    public function setOutputID($outputID)
    {
        $this->outputID = $this->preprocessOutputID($outputID);
        return $this;
    }

    public function setOutputElement($outputElement)
    {
        $this->outputElement = $outputElement;
        return $this;
    }

    public function setData($data)
    {
        //var_dump($data);
        $this->data = $this->preprocessData($data);
        return $this;
    }

    public function setLoadingHTML(string $loadingHTML)
    {
        $this->loadingHTML = $loadingHTML;
        return $this;
    }

    private function preprocessData($data)
    {
        if (!is_string($data)) {

            if ($data instanceof Form) {
                // var_dump($data);
                $data = '$("#' . $data->getId() . '").serializeArray()';
            }
            if (is_array($data)) {
                $data = json_encode($data);
            }
        }

        return $data;
    }

    private function preprocessOutputID($outputID)
    {
        if ($outputID instanceof FormElement) {
            $outputID = $outputID->getId();
        }

        return $outputID;
    }

    /**
     * Returns a core AJAX call function to be placed into a listener
     *
     * @return type
     */
    public function print()
    {
        $ajax = Javascript::buildAJAXRequest($this->targetURL, $this->outputID, $this->data, $this->getLoadingHTML(), $this->outputElement);
        return $ajax;
    }

}
