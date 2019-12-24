<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\api;

/**
 * Description of Draw
 *
 * @author cjacobsen
 */
use app\controllers\Controller;
use system\Post;
use app\models\Query;

class Query extends Controller {

    //put your code here
    public function post() {

        $post = Post::getKey('query');
        var_dump($post);
        \system\Database::get()->query($post);
    }

}
