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

    /**
     *
     * @param type $to The email address to send a test to.
     */
    public function test($to = null) {
        //var_dump($to);
        if ($this->user->privilege >= \app\models\user\Privilege::ADMIN) {
            \system\app\Email::sendTest($to);
        }
    }

}
