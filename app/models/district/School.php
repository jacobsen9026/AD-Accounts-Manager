<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\district;

/**
 * Description of School
 *
 * @author cjacobsen
 */
use app\models\DatabaseModel;

class School extends DatabaseModel {

    private $name;
    private $abbr;
    private $ou;

    public function importFromAD($LDAPResponse) {
        \system\app\AppLogger::get()->debug($LDAPResponse);

        if (key_exists("displayname", $LDAPResponse))
            $this->setName($LDAPResponse["displayname"][0]);
        $this->setAbbr($LDAPResponse["ou"][0]);
        $this->setOU($LDAPResponse["distinguishedname"][0]);
    }

    public function getName() {
        return $this->name;
    }

    public function getAbbr() {
        return $this->abbr;
    }

    public function getOu() {
        return $this->ou;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setAbbr($abbr) {
        $this->abbr = $abbr;
        return $this;
    }

    public function setOu($ou) {
        $this->ou = $ou;
        return $this;
    }

}
