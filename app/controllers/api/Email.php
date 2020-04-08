<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\api;

/**
 * Description of Email
 *
 * @author cjacobsen
 */
class Email extends APIController {

    public function test() {
        if ($this->user->privilege >= \app\models\user\Privilege::ADMIN) {
            \system\app\Email::sendTest();
        }
    }

}
