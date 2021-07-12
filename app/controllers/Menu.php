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
use App\Models\User\User;
use App\App\App;
use App\Models\User\PermissionHandler;
use System\Lang;
use System\Request;

class Menu extends Controller
{

    /**
     *
     * @var TopMenuItem
     */
    public $items;
    public $layout;
    public $config;

    /** @var User|null The web user */
    public $user;

    /** @var AppLogger|null The app logger */
    public $logger;

    function __construct($user, $layout = 'default')
    {

        $this->user = $user;
        $this->layout = $layout;
        $this->logger = AppLogger::get();

        $this->logger->info('Menu Creation Started UserPrivilege:' . $this->user->privilege);
        /*
         * Create top level items
         */

        if (PermissionHandler::hasUserPermissions()) {
            $this->items[] = $this->buildUserMenu();
        }
        if (PermissionHandler::hasGroupPermissions()) {
            $this->items[] = $this->buildGroupsMenu();
        }
        if ($this->user->superAdmin) {
            //$this->logger->debug("Building Parent and Staff Menus");
            //$this->items[] = $this->buildParentMenu();
        }


        if ($this->user->superAdmin && Request::get()->getServerName() != 'demo.adam-app.gq') {
            $this->items[] = $this->buildComputerMenu();
        }
    }

    private function buildUserMenu()
    {
        /*
         * Build Student Menu
         */

        $students = new TopMenuItem(Lang::get("Users"));
        $students->setTargetURL('/users');
        return $students;
    }

    private function buildGroupsMenu()
    {
        /*
         * Build Student Menu
         */

        $groups = new TopMenuItem(Lang::get("Groups"));
        $groups->setTargetURL('/groups');

        return $groups;
    }

    private function buildComputerMenu()
    {
        /*
         * Build Tech Menu
         */
        $computers = new TopMenuItem('Computers');
        $computers->setTargetURL('/computers');

        return $computers;
    }

    /**
     * The layout name referring to the prefix of the layout file
     *
     * @param string $layoutName
     *
     * @return string
     */
    public function getMenu(string $layoutName)
    {
        $this->app = App::get();


        //$this->logger->debug($this->items);
        return $this->view('/layouts/navbar');
    }

    /**
     * @return TopMenuItem
     * @deprecated
     */
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

}
