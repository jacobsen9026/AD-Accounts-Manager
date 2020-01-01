<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\api;

/**
 * Description of LDAP
 *
 * @author cjacobsen
 */
class LDAP {

    //put your code here

    public function testPermsPost() {
        $districtID = \system\Post::get("districtID");
        $testResult = $this->getPermissionCheckResult($districtID);
        if ($testResult == 'true') {
            return '<h1><i class="fas fa-check-circle text-success"></i></h1>';
        } else {
            return '<h1><i class="fas fa-times-circle text-danger"></i></h1>' . $testResult;
        }
    }

    private function getPermissionCheckResult($districtID) {
        $ad = new \app\api\AD($districtID);
        $testResult = $ad->createTestUser();
        // var_dump($testResult);
        return $testResult;
    }

}
