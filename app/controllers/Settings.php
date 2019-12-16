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

namespace app\controllers;

/**
 * Description of Home
 *
 * @author cjacobsen
 */
use system\Post;
use app\models\district\District;

class Settings extends Controller {

    public $postables;

    function __construct($app) {
        parent::__construct($app);
    }

    //put your code here
    public function index() {


        return $this->view('settings/index');
    }

    public function indexPost() {
        $post = Post::getAll();
        foreach ($post as $key => $value) {

            //var_dump($this->postables);
            if (in_array($key, $this->postables)) {
                var_dump($key);
                if ($value == "checkbox") {
                    $value = null;
                    $value = Post::get($key . '-checkbox');
                }
                $brokenKey = explode("-", $key);
                $config = $brokenKey[0];
                $configOption = ucfirst(strtolower($brokenKey[1]));
                $this->app->logger->debug($config);
                $this->app->logger->debug($configOption);
                $method = 'set' . $configOption;
                $this->app->logger->debug($method);
                if (!is_null($value)) {
                    $this->config->$config->$method($value);
                    $this->config->saveConfig();
                    return $this->view('settings/index');
                }
            }
            //var_dump($key);
            //var_dump(strpos($key, "rict-district"));
            //var_dump($_REQUEST);
            if (strpos($key, "istrict-")) {
                District::post(str_replace("district-", "", $key), $value);
            }
        }
    }

    /**
     * Write the database schema as constants to a file for the IDE
     */
    public function updateSchema() {

        $constantsTable = \system\Database::get()->getConstants();
        //var_dump($constantsTable);
        if (!empty($constantsTable)) {
            \system\File::refreshSchemaDefinitions($constantsTable);
        }
    }

}

?>
