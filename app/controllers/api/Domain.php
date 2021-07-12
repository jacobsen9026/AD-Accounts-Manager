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

namespace App\Controllers\Api;

/**
 * Description of LDAP
 *
 * @author cjacobsen
 */

use App\Api\AD;
use App\Api\Ad\ADComputers;
use App\Api\Ad\ADGroups;
use App\Api\Ad\ADUsers;
use App\Models\Database\DomainDatabase;
use App\Models\Domain\DomainComputer;
use App\Models\Domain\DomainUser;
use System\App\LDAPLogger;
use system\Post;

class Domain extends APIController
{

    /**
     * Uses the settings in the database to test create a random user
     * under the Base DN defined. The test user is then deleted.
     *
     * Returns a check box icon if successful, returns and red x if it fails.
     *
     * @return string
     */
    public function testPerms()
    {
        if ($this->user->superAdmin) {
            $domainID = 1;
            $testResult = $this->getPermissionCheckResult($domainID);
            if ($testResult == 'true') {
                return '<h1><i class="fas fa-check-circle text-success"></i></h1>';
            }

            return '<h1><i class="fas fa-times-circle text-danger"></i></h1>' . $testResult;
        }
        return '';
    }

    /**
     * Creates a test user under the configured Domain
     * BaseDN. If the randomly named user is successfully
     * made the user is deleted and the result is returned
     *
     *
     * @param int $domainID The database domainID
     *
     * @return string
     */
    private function getPermissionCheckResult(int $domainID)
    {

        if ($this->user->superAdmin) {
            $ad = new AD($domainID);
            $testResult = $ad->createTestUser();
            LDAPLogger::get()->debug($testResult);
            return $testResult;
        }
        return '';
    }

    /**
     * District ID's are being dropped use non post version of this method
     *
     * @return string
     * @deprecated since version number
     */
    public function testPermsPost()
    {

        if ($this->user->superAdmin) {
            $domainID = Post::get("districtID");
            $testResult = $this->getPermissionCheckResult($domainID);
            if ($testResult == 'true') {
                return '<h1><i class="fas fa-check-circle text-success"></i></h1>';
            }

            return '<h1><i class="fas fa-times-circle text-danger"></i></h1>' . $testResult;
        }
        return '';
    }

    /**
     * Prints an array of matching staff users
     *
     * @param string $searchTerm
     */
    public function autocompleteOU(string $searchTerm)
    {
        $appBaseDN = DomainDatabase::getAD_BaseDN(1);
        $searchTerm = $this->parseInput($searchTerm);
        $ous = AD::get()->listOUs($searchTerm);
        foreach ($ous as $ou) {
            $remove = [$appBaseDN, ','];
            $display = str_replace($remove, '', $ou);
            $parts = explode("OU=", $display);
            $parts = array_reverse($parts);
            $display = array_reduce($parts, function ($a, $b) {
                if ($a != null) {
                    return $a . "/" . $b;
                }
                return $b;
            });
            $display = substr($display, 0, -1);
            // $display = str_replace("OU=", '/', $display);
            $return[] = ["label" => $display, "value" => $ou];
            ksort($return);
        }
        return (["autocomplete" => $return]);
        //return (["autocomplete" => $ous]);
    }

    /**
     * Converts encoded spaces back to literal spaces
     *
     * @param string $input
     *
     * @return string
     */
    private function parseInput(string $input)
    {
        return urldecode($input);
        //return str_replace("%20", " ", $input);
    }

    /**
     * Prints an array of matching groups domain-wide
     *
     * @param string $searchTerm
     */
    public function autocompleteDomainGroup(string $searchTerm)
    {
        $searchTerm = $this->parseInput($searchTerm);
        $groups = AD::get()->listDomainGroups($searchTerm);
        if ($groups == false) {
            $groups = [];
        }
        //return $groups;
        return (["autocomplete" => $groups]);
    }

    /**
     * Prints an array of matching groups domain-wide
     *
     * @param string $searchTerm
     */
    public function autocompleteDomainUser(string $searchTerm)
    {
        $searchTerm = $this->parseInput($searchTerm);
        $users = AD::get()->listDomainUsers($searchTerm);
        if ($users == false) {
            $users = [];
        }
        //return $groups;
        return (["autocomplete" => $users]);
    }

    /**
     * Prints an array of matching groups domain-wide
     *
     * @param string $searchTerm
     */
    public function autocompleteComputer(string $searchTerm)
    {
        $searchTerm = $this->parseInput($searchTerm);
        $computers = AD::get()->listComputers($searchTerm);
        if ($computers == false) {
            $computers = [];
        }
        //return $groups;
        return (["autocomplete" => $computers]);
    }


    /**
     * Prints an array of matching users and groups
     * respecting permissions
     *
     * @param string $searchTerm
     */
    public function autocompleteUserOrGroup(string $searchTerm)
    {
        return (["autocomplete" => array_merge($this->autocompleteUser($searchTerm)["autocomplete"], $this->autocompleteGroup($searchTerm)["autocomplete"])]);
    }

    /**
     * Prints an array of matching users for both student and staff groups
     * respecting permissions
     *
     * @param string $searchTerm
     */
    public function autocompleteUser(string $searchTerm)
    {

        //$users = AD::get()->listStaffUsers($searchTerm);
        //$users = array_merge($users, AD::get()->listStudentUsers($searchTerm));
        $users2 = ADUsers::listUsers($searchTerm);
        $list = [];
        foreach ($users2 as $user) {
            $domainUser = new DomainUser($user);
            if ($domainUser->getDisplayName() != '' || $domainUser->getDisplayName() != ' ') {
                $label = $domainUser->getDisplayName();
            } else {
                $label = $domainUser->getUsername();
            }
            $list [] = ["label" => $label, "value" => $domainUser->getUsername()];
        }
        return (["autocomplete" => $list]);
    }

    /**
     * Prints an array of matching groups for both student and staff groups
     * respecting permissions
     *
     * @param string $searchTerm
     */
    public function autocompleteGroup(string $searchTerm)
    {
        $groups = $this->searchGroups($searchTerm);
        //   $groups = array_merge($groups, $this->searchStaffGroups($searchTerm));
        //wvar_dump($groups);
        //$groups = ad::get()->listGroups($searchTerm);
        return (["autocomplete" => $groups]);
    }

    /**
     * Returns an array of matching staff groups
     *
     * @param string $searchTerm
     *
     * @return array
     */
    private function searchGroups(string $searchTerm)
    {
        $groups = ADGroups::listGroups($searchTerm);
        if ($groups == false) {
            $groups = [];
        }
        return ($groups);


    }


}
