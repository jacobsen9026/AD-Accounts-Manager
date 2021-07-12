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

namespace App\Controllers\Api;

/**
 * Description of APIController
 *
 * @author cjacobsen
 */

use App\Controllers\Controller;
use App\Models\View\Toast;
use System\Request;

class APIController extends Controller
{

    //put your code here
    public function __construct(\App\App\App $app)
    {
        parent::__construct($app);
        Request::get()->setType('ajax');
        //var_dump(Request::get());
    }

    public function indexPost()
    {
        $action = \system\Post::get("action");
        if (method_exists($this, $action)) {
            return $this->$action();
        }
    }

    public function indexGet()
    {

        $action = \system\Get::get("action");
        /**
         * Placeholder for user Token verification
         */
        if (method_exists($this, $action)) {
            //var_dump("Method Exists");
            return $this->$action();
        }
    }

    public function returnHTML($html)
    {
        //$pageHookScript = '<script>preparePageHooks();</script>';
        return ["html" => $html];
    }

    public function returnValue($html)
    {
        return ["val" => $html];
    }

    public function returnAutoComplete($autoCompletionArray)
    {
        return ["autocomplete" => $autoCompletionArray];
    }

    public function settingsSavedToast()
    {
        $toast = new Toast('Settings Saved', '', 3000);
        $toast->setImage('<i class="far fa-save"></i>');
        return $toast->printToast();
    }


}
