<?php

/*
 * The MIT License
 *
 * Copyright 2019 cjacobsen.
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

/**
 * Description of Menu
 *
 * @author cjacobsen
 */

namespace App\Controllers;

use App\Controllers\Menu\TopMenuItem;
use App\Controllers\Menu\SubMenuItem;
use System\App\AppLogger;
use System\Parser;
use App\Models\User\User;
use App\App\App;
use App\Models\User\PermissionHandler;

class Menu extends Parser
{

    /**
     *
     * @var TopMenuItem
     */
    public $items;
    public $layout;
    public $config;

    /** @var User|null The view parser */
    public $user;

    /** @var AppLogger|null The app logger */
    public $logger;

    function __construct($user, $layout = 'default')
    {

        $this->user = $user;
        //$this->config = MasterConfig::get();
        $this->layout = $layout;
        $this->logger = AppLogger::get();

        $this->logger->info('Menu Creation Started UserPrivilege:' . $this->user->privilege);
        /*
         * Create top level items
         */

        if (PermissionHandler::hasUserPermissions()) {
            $this->items[] = $this->buildUserMenu();
            //$this->items[] = $this->buildStudentMenu();
            /** Combine these two buttons into a user button */
            //$this->items[] = $this->buildStaffMenu();
        }
        if (PermissionHandler::hasGroupPermissions()) {
            $this->items[] = $this->buildGroupsMenu();
        }
        if ($this->user->superAdmin) {
            $this->logger->debug("Building Parent and Staff Menus");
            //$this->items[] = $this->buildParentMenu();
        }


        if ($this->user->superAdmin) {
            $this->items[] = $this->buildTechMenu();
        }
    }

    private function buildUserMenu()
    {
        /*
         * Build Student Menu
         */

        $students = new TopMenuItem('Users');
        $students->setTargetURL('/users');
        return $students;
    }

    private function buildGroupsMenu()
    {
        /*
         * Build Student Menu
         */

        $groups = new TopMenuItem('Groups');
        $groups->setTargetURL('/groups');

        return $groups;
    }

    private function buildParentMenu()
    {
        /*
         * Build Parent Menu
         */
        $parents = new TopMenuItem('Parents');
        if ($parents) {
            $parents->addSubItem(new SubMenuItem('Google Groups Check', '/' . strtolower($parents->displayText) . '/get-groups'));
            $parents->addSubItem(new SubMenuItem('Manage Google Groups', '/' . strtolower($parents->displayText) . '/set-groups'));
        }
        return $parents;
    }

    private function buildTechMenu()
    {
        /*
         * Build Tech Menu
         */
        $tech = new TopMenuItem('Tech');
        if ($tech) {
            $tech->addSubItem(new SubMenuItem('Google', '/' . strtolower($tech->displayText) . '/google-accounts'));
            $tech->addSubItem(new SubMenuItem('Google Drive', '/' . strtolower($tech->displayText) . '/google-drive'));
            $tech->addSubItem(new SubMenuItem('Computers', '/' . strtolower($tech->displayText) . '/workstation-manage'));
        }
        return $tech;
    }

    /**
     * The layout name referring to the prefix of the layout file
     *
     * @param string $layoutName
     *
     * @return type
     */
    public function getMenu(string $layoutName)
    {
        $this->app = App::get();


        //$this->logger->debug($this->items);
        return $this->view('/layouts/navbar');
    }

}
