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
    public function testPerms() {
        $districtID = 1;
        $testResult = $this->getPermissionCheckResult($districtID);
        if ($testResult == 'true') {
            return '<h1><i class="fas fa-check-circle text-success"></i></h1>';
        } else {
            return '<h1><i class="fas fa-times-circle text-danger"></i></h1>' . $testResult;
        }
    }

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

    public function autocompleteStudent($username) {
        $users = \app\api\AD::get()->listStudentUsers($username);
        echo $this->formatUsers($users);
        exit;
    }

    private function formatUsers($users) {
        $output = "";
        $first = true;
        foreach ($users as $user) {
            if (!$first) {
                $output .= ',';
            }
            $output .= ' "' . $user . '" ';
            $first = false;
        }
        return '[' . $output . ']';
        //echo '["' . $users . 'Test", "anotheruser"]';
    }

    public function autocompleteStaff($username) {
        $users = \app\api\AD::get()->listStaffUsers($username);
        echo $this->formatUsers($users);
        exit;
    }

}
