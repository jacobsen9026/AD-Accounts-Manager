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
class LDAP extends APIController {

    //put your code here
    public function testPerms() {
        if ($this->user->privilege >= \app\models\user\Privilege::TECH) {
            $districtID = 1;
            $testResult = $this->getPermissionCheckResult($districtID);
            if ($testResult == 'true') {
                return '<h1><i class="fas fa-check-circle text-success"></i></h1>';
            } else {
                return '<h1><i class="fas fa-times-circle text-danger"></i></h1>' . $testResult;
            }
        }
    }

    public function testPermsPost() {

        if ($this->user->privilege >= \app\models\user\Privilege::TECH) {
            $districtID = \system\Post::get("districtID");
            $testResult = $this->getPermissionCheckResult($districtID);
            if ($testResult == 'true') {
                return '<h1><i class="fas fa-check-circle text-success"></i></h1>';
            } else {
                return '<h1><i class="fas fa-times-circle text-danger"></i></h1>' . $testResult;
            }
        }
    }

    private function getPermissionCheckResult($districtID) {

        if ($this->user->privilege >= \app\models\user\Privilege::TECH) {
            $ad = new \app\api\AD($districtID);
            $testResult = $ad->createTestUser();
            // var_dump($testResult);
            return $testResult;
        }
    }

    /**
     * Used for form input autocompletion
     *
     * @param type $searchTerm
     */
    public function autocompleteStudent($searchTerm) {
        $searchTerm = $this->parseInput($searchTerm);
        $users = \app\api\AD::get()->listStudentUsers($searchTerm);
        echo $this->packageList($users);
    }

    /**
     * Packages the autocomplete output
     * @param type $items
     * @return type
     */
    private function packageList($items) {
        $output = "";
        $first = true;
        foreach ($items as $item) {
            if (!$first) {
                $output .= ',';
            }
            if (is_array($item)) {
                $output .= ' { "label": "' . $item["label"] . '", "value": "' . $item["value"] . '"} ';
            } else {
                $output .= ' "' . $item . '" ';
            }
            $first = false;
        }
        return '[' . $output . ']';
        //echo '["' . $users . 'Test", "anotheruser"]';
    }

    private function parseInput($input) {
        return str_replace("%20", " ", $input);
    }

    public function autocompleteStaff($username) {

        $username = $this->parseInput($username);
        $users = \app\api\AD::get()->listStaffUsers($username);
        echo $this->packageList($users);
    }

    public function autocompleteStudentGroup($group) {
        $group = $this->parseInput($group);
        $groups = \app\api\AD::get()->listStudentGroups($group);
        echo $this->packageList($groups);
    }

}
