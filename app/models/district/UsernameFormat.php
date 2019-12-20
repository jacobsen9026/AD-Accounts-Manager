<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\district;

/**
 * Description of UsernameFormat
 *
 * @author cjacobsen
 */
abstract class UsernameFormat {

    //put your code here
    const FILN = array('{First Initial}{Last Name}', 'uf_filn');
    const YOGFILN = array('{Year of Grad}{First Initial}{Last Name}', 'uf_yogfiln');

    public static function getUsernameFormats() {
        return array(self::FILN, self::YOGFILN);
    }

    public static function formatUsername($firstName, $lastName, $format, $yog = null, $middleName = null) {
        switch ($format) {
            case self::FILN:

                return substr($firstName, 0, 1) . $lastName;
                break;
            case self::YOGFILN:

                return $yog . substr($firstName, 0, 1) . $lastName;
                break;
            default:
                break;
        }
    }

}
