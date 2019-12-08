<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\auth;

/**
 * Description of Tokenizer
 *
 * @author cjacobsen
 */
class Tokenizer {

    //put your code here

    private $tokenPath;

    function __construct() {
        $this->tokenPath = APPPATH . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'tokens' . DIRECTORY_SEPARATOR;
    }

    public function saveUserToken(User $user) {

        $userHash = hash('sha256', $user);
    }

}
