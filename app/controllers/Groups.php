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

namespace App\Controllers;

/**
 * Description of Groups
 *
 * @author cjacobsen
 */
use System\Post;
use App\Models\District\DistrictUser;
use App\Models\District\Group;
use App\Api\AD;

class Groups extends Controller {

    public function index() {
        $return = $this->view('/groups/search');
        //$return .= $this->view('/groups/create');


        return $return;
    }

    public function createPost() {
        /**
          $validator = new \System\App\Forms\Validators\GroupValidator();
          $validator->setMethod(\System\App\Forms\Validators\GroupValidator::ADD_GROUP);
          $validator->setName(Post::get("name"))
          ->setDescription(Post::get("description"))
          ->setEmail(Post::get("email"))
          ->setOu(Post::get("ou"));
          $filteredInput = $validator->validateInput();
          return;
         *
         */
        $name = Post::get("name");
        $desc = Post::get("description");
        $email = Post::get("email");
        $ou = Post::get("ou");
        if ($name != null and $ou != null) {
            $newGroup = new Group();
            $dn = "CN=" . Post::get("name") . ',' . Post::get("ou");
            $newGroup->setName(Post::get("name"))
                    ->setDistinguishedName($dn);
            $newGroup->createInAD();
        }
    }

    /**
     * Searches for groups by name and returns a display view
     * @param string $groupName
     * @return type
     */
    public function search(string $groupName) {
        $ad = AD::get();
        $this->group = new \App\Models\District\Group();
        $this->group->importFromAD($ad->searchGroup($groupName));

        //var_dump($this->group);
        $return = $this->view('/groups/show');
        //$return .= $this->view('/groups/create');
        return $return;
    }

    /**
     * Search but by post
     * @param type $groupName
     * @return type
     */
    public function searchPost($groupName = null) {
        $group = Post::get("group");
        if (!is_null($groupName)) {
            return $this->search($groupName);
        } else {
            /**
             * If I want to not require the group in the url I can use this
             * for post only requests. Can check the $group variable
             */
        }
    }

    /**
     * Handles all group changes by Post
     * @return type
     */
    public function editPost() {
        $action = Post::get('action');
        switch ($action) {
            case 'removeMember':
                $username = Post::get('username');
                $this->logger->info("removing member " . $username);

                $groupName = Post::get('group');
                $ad = AD::get();
                $adGroupRaw = $ad->getGroup($groupName);
                if ($this->user->privilege >= \App\Models\User\Privilege::ADMIN) {
                    if ($adGroupRaw == false) {
                        $adGroupRaw = $ad->getStaffGroup($groupName);
                    }
                }



                $group = new \App\Models\District\Group();
                $group->importFromAD($adGroupRaw);
                $user = $group->hasMember($username);
                if ($user != false) {
                    if ($group->removeMember($user)) {

                        $this->logger->debug("User was successfully removed");
                        return $this->search($group->getName());
                    }
                }
                $this->logger->debug($group);

                break;
            case 'addMember':
                $username = Post::get('usernameToAdd');

                $ad = AD::get();
                $this->logger->info("adding member " . $username);
                $groupName = Post::get('groupName');
                $group = new Group();
                $group->importFromAD($ad->getGroup($groupName));
                $user = new DistrictUser($username);
                if ($ad->addUserToGroup($group->getDistinguishedName(), $user->getDistinguishedName())) {
                    $this->logger->debug("User was successfully removed");
                    return $this->search($group->getName());
                }

                break;
            default:
                break;
        }
    }

    public function deletePost() {
        $groupDN = Post::get("groupDN");
        $ad = AD::get();
        $ad->deleteGroup($groupDN);
    }

}
