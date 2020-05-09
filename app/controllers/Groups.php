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

namespace app\controllers;

/**
 * Description of Groups
 *
 * @author cjacobsen
 */
use system\Post;

class Groups extends Controller {

    public function index() {

        return $this->view('/groups/search');
    }

    public function search(string $groupName) {
        $ad = \app\api\AD::get();
        $this->group = new \app\models\district\Group();
        $this->group->importFromAD($ad->searchGroup($groupName));
        return $this->view('/groups/show');
    }

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

    public function editPost() {
        $action = Post::get('action');
        switch ($action) {
            case 'removeMember':
                $username = Post::get('username');
                $this->logger->info("removing member " . $username);

                $groupName = Post::get('group');
                $ad = \app\api\AD::get();
                $adGroupRaw = $ad->getStudentGroup($groupName);
                if ($this->user->privilege >= \app\models\user\Privilege::ADMIN) {
                    if ($adGroupRaw == false) {
                        $adGroupRaw = $ad->getStaffGroup($groupName);
                    }
                }



                $group = new \app\models\district\Group();
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

            default:
                break;
        }
    }

}
