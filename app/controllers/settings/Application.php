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

namespace app\controllers\settings;

/**
 * Description of Home
 *
 * @author cjacobsen
 */
use system\Post;
use app\models\district\District;
use app\database\Schema;
use app\controllers\Controller;

class Application extends Controller {

    public $postables;

    function __construct($app) {
        parent::__construct($app);
    }

    //put your code here
    public function index() {


        return $this->view('settings/index');
    }

    public function indexPost() {
        \system\app\AppLogger::get()->debug('Edit Post');
        $post = \system\Post::getAll();
        var_dump($post);
        \app\models\DatabasePost::setPost(Schema::APP, \system\app\App::getID(), $post);
        //var_dump($post);
        $this->redirect('/settings/application');
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
