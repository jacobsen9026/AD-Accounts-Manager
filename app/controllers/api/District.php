<?php

/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace app\controllers\api;

/**
 * Description of LDAP
 *
 * @author cjacobsen
 */
class District extends APIController {

    /**
     * Uses the settings in the database to test create a random user
     * under the Base DN defined. The test user is then deleted.
     *
     * Returns a check box icon if successful, returns and red x if it fails.
     *
     * @return string
     */
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

    /**
     * District ID's are being dropped use non post version of this method
     * @return string
     * @deprecated since version number
     */
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

    /**
     * Creates a test user under the configured District
     * BaseDN. If the randomly named user is successfully
     * made the user is deleted and the result is returned
     *
     *
     * @param int $districtID The database districtID
     * @return string
     */
    private function getPermissionCheckResult(int $districtID) {

        if ($this->user->privilege >= \app\models\user\Privilege::TECH) {
            $ad = new \app\api\AD($districtID);
            $testResult = $ad->createTestUser();
            // var_dump($testResult);
            return $testResult;
        }
    }

    /**
     * Used for form input auto-completion
     *
     * @param string $searchTerm
     */
    public function autocompleteStudent(string $searchTerm) {
        $searchTerm = $this->parseInput($searchTerm);
        $users = \app\api\AD::get()->listStudentUsers($searchTerm);
        echo json_encode($users);
    }

    /**
     * Converts encoded spaces back to literal spaces
     * @param string $input
     * @return type
     */
    private function parseInput(string $input) {
        return str_replace("%20", " ", $input);
    }

    /**
     * Prints an array of matching staff users
     * @param string $username
     */
    public function autocompleteStaff(string $username) {

        $username = $this->parseInput($username);
        $users = \app\api\AD::get()->listStaffUsers($username);
        echo json_encode($users);
    }

    /**
     * Prints an array of matching groups for both student and staff groups
     * respecting permissions
     * @param type $searchTerm
     */
    public function autocompleteGroup(string $searchTerm) {
        $groups = $this->searchStudentGroups($searchTerm);
        $groups = array_merge($groups, $this->searchStaffGroups($searchTerm));
        echo json_encode($groups);
    }

    /**
     * Returns and array of matching staff groups
     * @param type $searchTerm
     * @return array
     */
    private function searchStaffGroups(string $searchTerm) {
        $groups = \app\api\AD::get()->listStaffGroups($searchTerm);
        if ($groups == false) {
            $groups = array();
        }
        return $groups;
    }

    /**
     * Returns an array of matching staff groups
     * @param string $searchTerm
     * @return array
     */
    private function searchStudentGroups(string $searchTerm) {
        $groups = \app\api\AD::get()->listStudentGroups($searchTerm);
        if ($groups == false) {
            $groups = array();
        }
        return $groups;
    }

    /**
     * Prints an array of matching groups domain-wide
     * 
     * @param string $searchTerm
     */
    public function autocompleteDomainGroup(string $searchTerm) {
        $searchTerm = $this->parseInput($searchTerm);
        $groups = \app\api\AD::get()->listDomainGroups($searchTerm);
        if ($groups == false) {
            $groups = array();
        }
        //return $groups;
        echo json_encode($groups);
    }

}
