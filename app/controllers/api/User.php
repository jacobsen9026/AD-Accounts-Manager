<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\api;

/**
 * Description of User
 *
 * @author cjacobsen
 */
class User extends APIController {

    public function newAPIKey() {
        //return hash("sha256", random_bytes(256));
        $this->user->generateAPIToken();
        $this->user->save();
        return $this->user->getApiToken();
    }

}
